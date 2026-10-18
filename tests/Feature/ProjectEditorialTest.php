<?php

namespace Tests\Feature;

use App\Filament\Resources\MediaAssetResource\Pages\EditMediaAsset;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Models\ContentRecord;
use App\Models\MediaAsset;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\OrganizationLogoSeeder;
use Database\Seeders\PortfolioSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectEditorialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(PortfolioSeeder::class);
    }

    private function admin(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $user->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($user);
    }

    private function image(string $name): MediaAsset
    {
        return MediaAsset::create(['title' => $name, 'alt_text' => $name,
            'path' => UploadedFile::fake()->image($name.'.png', 200, 100)->store('uploads', 'local')]);
    }

    public function test_confirmed_project_details_and_race_director_role_are_published_consistently(): void
    {
        $this->get('/projecten/globetrotr')->assertOk()->assertSee('Oprichter &amp; Full-stack Developer', false)
            ->assertSee('Supabase')->assertSee('Row Level Security')->assertSee('Leaflet.js');
        $this->get('/projecten/dutchunitedracing')->assertOk()->assertSee('Oprichter &amp; Race Director', false)->assertSee('Assetto Corsa Competizione');
        $this->get('/cv')->assertSee('Oprichter &amp; Race Director', false);
        $this->get('/cv/print')->assertSee('Oprichter &amp; Race Director', false);
        $this->get('/projecten?techniek=supabase')->assertOk()->assertSee('globetrotr.nl')->assertDontSee('DutchUnitedRacing');
        $this->get('/projecten?q=Discord')->assertOk()->assertSee('DutchUnitedRacing')->assertDontSee('globetrotr.nl');
    }

    public function test_gallery_order_captions_and_logo_background_can_be_edited_in_the_portal(): void
    {
        $this->admin();
        $first = $this->image('Eerste screenshot');
        $second = $this->image('Tweede screenshot');
        Livewire::test(EditMediaAsset::class, ['record' => $second->id])->fillForm([
            'caption' => 'Het project in gebruik', 'background' => 'dark',
            'source_label' => 'Alleen voor administratie', 'source_url' => 'https://example.invalid/private-source',
        ])->call('save')->assertHasNoFormErrors();
        $project = Project::where('slug', 'globetrotr')->firstOrFail();
        Livewire::test(EditProject::class, ['record' => $project->id])->fillForm([
            'gallery' => [['media_id' => (string) $second->id], ['media_id' => (string) $first->id]], 'cover_media_id' => $first->id,
        ])->call('save')->assertHasNoFormErrors();
        $this->assertSame([$second->id, $first->id], $project->fresh()->gallery);
        Livewire::test(EditProject::class, ['record' => $project->id])->fillForm(['role' => 'Mijn eigen bijdrage'])->call('save')->assertHasNoFormErrors();
        $this->assertSame([$second->id, $first->id], $project->fresh()->gallery);
        $this->assertSame('dark', $second->fresh()->background);
        $this->get('/projecten/globetrotr')->assertOk()->assertSee('data-project-gallery', false)->assertSee('Het project in gebruik')
            ->assertSeeInOrder([$second->uuid, $first->uuid], false)->assertDontSee('example.invalid/private-source');
        $project->update(['status' => 'draft']);
        $this->get($second->publicUrl())->assertNotFound();
    }

    public function test_source_update_preserves_custom_fields_status_and_deleted_records(): void
    {
        $project = Project::where('slug', 'globetrotr')->firstOrFail();
        $project->update(['name' => 'Globetrotr', 'role' => null, 'technologies' => [], 'headline' => 'Mijn eigen projectkop', 'status' => 'draft']);
        $deleted = Project::where('slug', 'dutchunitedracing')->firstOrFail();
        $deleted->delete();
        $initiative = ContentRecord::where('key', 'initiative:0')->firstOrFail();
        $initiative->update(['title' => 'Oprichter']);

        $this->artisan('app:import-project-details')->assertSuccessful();
        $this->assertSame('GlobeTrotr', $project->fresh()->name);
        $this->assertSame('Oprichter & Full-stack Developer', $project->fresh()->role);
        $this->assertContains('Supabase', $project->fresh()->technologies);
        $this->assertSame('Mijn eigen projectkop', $project->fresh()->headline);
        $this->assertSame('draft', $project->fresh()->status);
        $this->assertTrue($deleted->fresh()->trashed());
        $this->assertSame('Oprichter & Race Director', $initiative->fresh()->title);
        $project->update(['summary' => 'Mijn eigen nieuwe tekst']);
        $this->artisan('app:import-project-details')->assertSuccessful();
        $this->assertSame('Mijn eigen nieuwe tekst', $project->fresh()->summary);
    }

    public function test_official_logo_import_reuses_assets_and_keeps_a_custom_logo(): void
    {
        $ns = Organization::where('name', 'Nederlandse Spoorwegen')->firstOrFail();
        $learning = Organization::where('name', 'NS Leercentrum')->firstOrFail();
        $this->assertNotNull($ns->logo_media_id);
        $this->assertSame($ns->logo_media_id, $learning->logo_media_id);
        $this->assertSame('dark', Organization::where('name', 'Railcenter')->firstOrFail()->logo->background);
        $this->assertSame('https://www.ns.nl/', $ns->logo->source_url);
        $this->get($ns->logo->publicUrl())->assertOk()->assertHeader('Content-Type', 'image/webp');
        $custom = $this->image('Mijn eigen NS-logo');
        $ns->update(['logo_media_id' => $custom->id]);
        $count = MediaAsset::count();
        $this->seed(OrganizationLogoSeeder::class);
        $this->assertSame($custom->id, $ns->fresh()->logo_media_id);
        $this->assertSame($count, MediaAsset::count());
    }
}
