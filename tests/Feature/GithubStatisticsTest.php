<?php

namespace Tests\Feature;

use App\Models\ContentRecord;
use App\Models\GithubSnapshot;
use App\Models\SiteProfile;
use App\Models\User;
use App\Filament\Resources\SiteProfileResource\Pages\EditSiteProfile;
use Filament\Facades\Filament;
use Livewire\Livewire;
use App\Services\GithubStatistics;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GithubStatisticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Http::preventStrayRequests();
        $this->seed(PortfolioSeeder::class);
    }

    private function profile(array $changes = []): SiteProfile
    {
        $profile = SiteProfile::firstOrFail();
        $profile->update(['settings' => array_replace($profile->settings, ['github_username' => 'ExampleDev', 'github_statistics' => true], $changes)]);
        return $profile;
    }

    private function repo(int $id, array $changes = []): array
    {
        return array_replace(['id' => $id, 'private' => false, 'fork' => false, 'archived' => false, 'owner' => ['login' => 'ExampleDev'], 'stargazers_count' => 2], $changes);
    }

    public function test_pagination_filters_and_deduplication_produce_a_complete_measurement(): void
    {
        $items = array_map(fn ($id) => $this->repo($id), range(1, 100));
        Http::fakeSequence()->push($items)->push([
            $this->repo(1), $this->repo(101, ['fork' => true]), $this->repo(102, ['archived' => true]),
            $this->repo(103, ['private' => true]), $this->repo(104, ['owner' => ['login' => 'Other']]), $this->repo(105),
        ]);
        $this->assertSame('GitHub-statistieken bijgewerkt.', app(GithubStatistics::class)->sync($this->profile()));
        $snapshot = GithubSnapshot::firstOrFail();
        $this->assertSame(101, $snapshot->repositories);
        $this->assertSame(202, $snapshot->stars);
        $this->assertNotNull($snapshot->synced_at);
        Http::assertSentCount(2);
        $this->get('/development')->assertOk()->assertSee('101')->assertSee('202')->assertSee('zonder forks en archieven');
    }

    public function test_rate_limits_and_malformed_pages_retain_the_previous_snapshot(): void
    {
        $profile = $this->profile();
        $snapshot = GithubSnapshot::create(['username' => 'exampledev', 'repositories' => 7, 'stars' => 9, 'synced_at' => now()->subDay()]);
        Http::fakeSequence()->push(['message' => 'rate limited'], 429, ['Retry-After' => '900']);
        $this->assertStringContainsString('rate_limit', app(GithubStatistics::class)->sync($profile));
        $this->assertSame(7, $snapshot->fresh()->repositories);
        $this->assertTrue($snapshot->fresh()->retry_after->isFuture());
        app(GithubStatistics::class)->sync($profile);
        Http::assertSentCount(1);
        $this->travel(16)->minutes();
        Http::swap(new \Illuminate\Http\Client\Factory);
        Http::preventStrayRequests();
        Http::fakeSequence()->push(array_map(fn ($id) => $this->repo($id), range(1, 100)))->push(['invalid' => true]);
        $this->assertStringContainsString('invalid_response', app(GithubStatistics::class)->sync($profile));
        $this->assertSame(7, $snapshot->fresh()->repositories);
        $this->assertSame(9, $snapshot->fresh()->stars);
    }

    public function test_profile_links_publication_and_visibility_need_no_live_api(): void
    {
        $profile = $this->profile();
        GithubSnapshot::create(['username' => 'exampledev', 'repositories' => 12345, 'stars' => 6789, 'synced_at' => now()->subDays(3)]);
        $this->get('/contact')->assertOk()->assertSee('https://github.com/ExampleDev', false)->assertSee('Werkervaring & opleidingen', false);
        $this->get('/')->assertOk()->assertSee('12.345')->assertSee('ouder dan 48 uur');
        $this->profile(['github_statistics' => false]);
        $this->get('/')->assertDontSee('12.345');
        $this->profile(['github_username' => 'OtherDev']);
        $this->get('/')->assertDontSee('12.345');
        $this->profile(['show_development' => false]);
        $this->get('/contact')->assertDontSee('Development &amp; statistieken', false);
        $this->get('/')->assertDontSee('github-heading');
        ContentRecord::where('key', 'page:cv')->firstOrFail()->update(['status' => 'draft']);
        $this->get('/contact')->assertDontSee('Werkervaring & opleidingen', false)->assertDontSee('Cv afdrukken of bewaren');
        $profile->update(['status' => 'draft']);
        $this->get('/contact')->assertDontSee('https://github.com/OtherDev', false);
        Http::assertNothingSent();
    }

    public function test_disabled_invalid_and_locked_configurations_never_call_the_api(): void
    {
        app(GithubStatistics::class)->sync($this->profile(['github_statistics' => false]));
        app(GithubStatistics::class)->sync($this->profile(['github_username' => '../../bad']));
        $lock = Cache::lock('github-sync:exampledev', 180);
        $lock->get();
        try { $this->assertSame('Er draait al een synchronisatie.', app(GithubStatistics::class)->sync($this->profile())); }
        finally { $lock->release(); }
        Http::assertNothingSent();
    }

    public function test_failed_first_measurement_does_not_publish_zero_and_a_valid_empty_account_does(): void
    {
        $profile = $this->profile();
        Http::fakeSequence()->push(['message' => 'Unavailable'], 503)->push([]);
        $this->assertStringContainsString('api_unavailable', app(GithubStatistics::class)->sync($profile));
        $this->assertNull(GithubSnapshot::firstOrFail()->synced_at);
        $this->get('/development')->assertSee('Statistieken verschijnen na de eerste geslaagde meting.')->assertDontSee('github-metrics');
        $this->travel(6)->minutes();
        $this->assertSame('GitHub-statistieken bijgewerkt.', app(GithubStatistics::class)->sync($profile));
        $this->assertSame(0, GithubSnapshot::firstOrFail()->repositories);
        $this->get('/development')->assertSee('github-metrics')->assertDontSee('Statistieken verschijnen na de eerste geslaagde meting.');
    }

    public function test_setup_command_preserves_content_and_publication_status(): void
    {
        $profile = SiteProfile::firstOrFail();
        $profile->update(['status' => 'draft', 'intro' => 'Eigen redactie']);
        $this->artisan('app:configure-github', ['username' => 'ExampleDev', '--statistics' => true])->assertSuccessful();
        $this->assertSame('Eigen redactie', $profile->fresh()->intro);
        $this->assertSame('draft', $profile->fresh()->status);
        $this->assertTrue($profile->fresh()->settings['github_statistics']);
        $this->artisan('app:configure-github', ['username' => 'bad--name'])->assertFailed();
    }

    public function test_admin_can_refresh_the_saved_github_configuration_in_the_portal(): void
    {
        $profile = $this->profile();
        $admin = User::factory()->create(['is_admin' => true]);
        $admin->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        $this->actingAs($admin);
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Http::fakeSequence()->push([$this->repo(1)]);
        Livewire::test(EditSiteProfile::class, ['record' => $profile->id])->callAction('syncGithub')->assertHasNoErrors();
        $this->assertSame(1, GithubSnapshot::firstOrFail()->repositories);
        Http::assertSentCount(1);
    }
}
