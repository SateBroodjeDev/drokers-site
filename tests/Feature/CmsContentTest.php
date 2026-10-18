<?php

namespace Tests\Feature;

use App\Filament\Resources\ContentRecordResource\Pages\CreateContentRecord;
use App\Filament\Resources\SiteProfileResource\Pages\EditSiteProfile;
use App\Models\ContentRecord;
use App\Models\MediaAsset;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SiteProfile;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Tests\TestCase;

class CmsContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(PortfolioSeeder::class);
    }

    private function admin(): User
    {
        $user = User::factory()->create(['is_admin' => true]);
        $user->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($user);
        return $user;
    }

    private function image(): MediaAsset
    {
        $file = UploadedFile::fake()->image('bedrijfslogo.png', 180, 80)->store('uploads', 'local');
        return MediaAsset::create(['title' => 'Eigen bedrijfslogo', 'alt_text' => 'Bedrijfslogo', 'path' => $file]);
    }

    public function test_all_portal_lists_and_edit_forms_render_for_the_mfa_admin(): void
    {
        $this->admin();
        foreach ([
            'site-profiles' => SiteProfile::first()->id,
            'organizations' => Organization::first()->id,
            'content-records' => ContentRecord::first()->id,
            'projects' => Project::first()->id,
            'media-assets' => $this->image()->id,
        ] as $route => $id) {
            $this->get('/admin/'.$route)->assertOk();
            $this->get('/admin/'.$route.'/'.$id.'/edit')->assertOk();
        }
    }

    public function test_portal_changes_to_profile_and_style_reach_the_website_and_print(): void
    {
        $this->admin();
        $profile = SiteProfile::first();
        Livewire::test(EditSiteProfile::class, ['record' => $profile->id])
            ->fillForm(['name' => 'Nieuwe publieke naam', 'headline' => 'Mijn eigen ondertitel', 'settings.accent' => '#ed7231', 'settings.theme' => 'graphite', 'settings.welcome' => 'Even voorstellen', 'settings.career_heading' => 'Mijn loopbaan', 'settings.initiatives_heading' => 'Wat ik zelf begin'])
            ->call('save')->assertHasNoFormErrors();
        $this->get('/')->assertOk()->assertSee('Nieuwe publieke naam')->assertSee('--accent: #ed7231', false)
            ->assertSee('Even voorstellen')->assertSee('Mijn loopbaan')->assertSee('Wat ik zelf begin');
        $this->get('/cv/print')->assertOk()->assertSee('Nieuwe publieke naam')->assertSee('Mijn eigen ondertitel');
        Livewire::test(EditSiteProfile::class, ['record' => $profile->id])
            ->fillForm(['settings.accent' => 'red; background:url(https://invalid.example)'])
            ->call('save')->assertHasFormErrors(['settings.accent']);
    }

    public function test_new_experience_can_be_created_in_the_portal_and_published(): void
    {
        $this->admin();
        Livewire::test(CreateContentRecord::class)->fillForm([
            'kind' => 'work', 'title' => 'Nieuwe rol uit het portaal', 'summary' => 'Nieuwe inhoud', 'period' => '2026 — heden',
            'is_current' => true, 'data.responsibilities' => ['Een concrete verantwoordelijkheid'], 'sort_order' => 2,
        ])->call('create')->assertHasNoFormErrors();
        $record = ContentRecord::where('title', 'Nieuwe rol uit het portaal')->firstOrFail();
        $this->assertSame('draft', $record->status);
        $this->get('/cv')->assertDontSee('Nieuwe rol uit het portaal');
        Livewire::test(\App\Filament\Resources\ContentRecordResource\Pages\EditContentRecord::class, ['record' => $record->id])->callAction('publish')->assertHasNoFormErrors();
        $this->get('/cv')->assertSee('Nieuwe rol uit het portaal');
        $this->get('/cv/print')->assertSee('Nieuwe rol uit het portaal');
    }

    public function test_contact_page_uses_managed_public_links_without_exposing_the_admin_email(): void
    {
        $this->admin();
        $adminEmail = auth()->user()->email;
        $this->get('/contact')->assertOk()->assertSee('Contactgegevens volgen.')->assertDontSee($adminEmail);
        Livewire::test(EditSiteProfile::class, ['record' => SiteProfile::first()->id])
            ->fillForm(['settings.contact_heading' => 'Een professionele kennismaking', 'settings.contact_intro' => 'Neem gerust contact op voor een gesprek.',
                'settings.links' => [
                    ['label' => 'Stuur een bericht', 'url' => 'mailto:publiek@example.test', 'description' => 'Voor vragen en kennismakingen'],
                    ['label' => 'Mijn profiel', 'url' => 'https://example.test/profiel', 'description' => 'Mijn professionele achtergrond'],
                ]])
            ->call('save')->assertHasNoFormErrors();
        $this->get('/contact')->assertOk()->assertSee('Een professionele kennismaking')->assertSee('Neem gerust contact op voor een gesprek.')
            ->assertSee('mailto:publiek@example.test', false)->assertSee('https://example.test/profiel', false)
            ->assertSee('Voor vragen en kennismakingen')->assertDontSee('Contactgegevens volgen.')->assertDontSee($adminEmail);
        ContentRecord::where('key', 'page:cv')->firstOrFail()->update(['status' => 'draft']);
        $this->get('/contact')->assertOk()->assertDontSee('Cv afdrukken of bewaren')->assertDontSee('Werkervaring &amp; opleidingen', false);
    }

    public function test_drafts_archives_and_deletions_never_leak_into_projects_or_counts(): void
    {
        $project = Project::where('slug', 'globetrotr')->firstOrFail();
        foreach (['draft', 'archived'] as $status) {
            $project->update(['status' => $status]);
            $this->get('/projecten')->assertDontSee('globetrotr.nl');
            $this->get('/projecten/globetrotr')->assertNotFound();
            $this->get('/development')->assertSee('02');
        }
        $project->publish();
        $project->delete();
        $this->get('/projecten/globetrotr')->assertNotFound();
        $this->get('/')->assertDontSee('globetrotr.nl');
    }

    public function test_homepage_career_uses_published_work_and_respects_page_and_logo_visibility(): void
    {
        $work = ContentRecord::where('kind', 'work')->firstOrFail();
        $work->update(['title' => 'Herkenbare rol voor de homepage', 'sort_order' => 0]);
        $this->get('/')->assertOk()->assertSee('Herkenbare rol voor de homepage');
        foreach (['draft', 'archived'] as $status) {
            $work->update(['status' => $status]);
            $this->get('/')->assertDontSee('Herkenbare rol voor de homepage');
        }
        $work->publish();
        $logo = $this->image();
        $organization = $work->organization;
        $organization->update(['logo_media_id' => $logo->id]);
        $this->get('/')->assertSee($logo->uuid);
        $organization->update(['status' => 'draft']);
        $this->get('/')->assertDontSee($logo->uuid)->assertSee('Herkenbare rol voor de homepage');
        $work->delete();
        $this->get('/')->assertDontSee('Herkenbare rol voor de homepage');
        ContentRecord::where('key', 'page:cv')->firstOrFail()->update(['status' => 'draft']);
        $this->get('/')->assertOk()->assertDontSee('id="career-heading"', false)->assertDontSee('Bekijk mijn cv');
    }

    public function test_appearance_can_be_changed_in_the_portal_and_interest_cards_only_show_published_stories(): void
    {
        $this->get('/')->assertOk()->assertSee('data-appearance="night"', false)->assertSee('id="interests-heading"', false);
        $story = ContentRecord::where('key', 'world:code')->firstOrFail();
        $story->update(['title' => 'Herkenbare persoonlijke interesse']);
        $this->get('/')->assertSee('Herkenbare persoonlijke interesse')->assertSee('interesse=code');
        $story->update(['status' => 'draft']);
        $this->get('/')->assertDontSee('Herkenbare persoonlijke interesse')->assertDontSee('interesse=code');
        $this->admin();
        Livewire::test(EditSiteProfile::class, ['record' => SiteProfile::first()->id])
            ->fillForm(['settings.appearance' => 'light', 'settings.interests_heading' => 'Mijn persoonlijke interesses'])
            ->call('save')->assertHasNoFormErrors();
        $this->get('/')->assertOk()->assertSee('data-appearance="light"', false)->assertSee('--paper: #f2f3f6', false)->assertSee('Mijn persoonlijke interesses')
            ->assertViewHas('site', fn (array $site) => $site['palettes']['night']['background'] === '#10121f' && $site['palettes']['light']['background'] === '#f2f3f6');
        Livewire::test(EditSiteProfile::class, ['record' => SiteProfile::first()->id])
            ->fillForm(['settings.background' => '#fff9f0', 'settings.ink' => '#202030', 'settings.accent' => '#ef8045'])
            ->call('save')->assertHasNoFormErrors();
        $this->get('/')->assertViewHas('site', fn (array $site) => $site['palettes']['light']['background'] === '#fff9f0'
            && $site['palettes']['night']['background'] === '#10121f' && $site['palettes']['night']['ink'] === '#f2eff7'
            && $site['palettes']['light']['accent'] === $site['palettes']['night']['accent']);
        $this->get('/cv/print')->assertOk()->assertSee('Domenic Rokers');
    }

    public function test_logo_is_shared_between_roles_and_only_served_when_referenced_publicly(): void
    {
        $logo = $this->image();
        $org = Organization::where('name', 'Nederlandse Spoorwegen')->firstOrFail();
        $this->get($logo->publicUrl())->assertNotFound();
        $org->update(['logo_media_id' => $logo->id]);
        $this->get($logo->publicUrl())->assertOk()->assertHeader('Content-Type', 'image/webp');
        $response = $this->get('/cv')->assertOk();
        $this->assertGreaterThanOrEqual(2, substr_count($response->getContent(), $logo->uuid));
        $this->get('/cv/print')->assertOk()->assertSee($logo->uuid);
        $org->update(['status' => 'draft']);
        $this->get($logo->publicUrl())->assertNotFound();
        $this->get('/cv')->assertDontSee($logo->uuid);
    }

    public function test_preview_and_private_media_require_an_active_admin_with_mfa(): void
    {
        $project = Project::first();
        $project->update(['status' => 'draft']);
        $image = $this->image();
        $preview = route('cms.preview', ['type' => 'projects', 'id' => $project->id]);
        $this->get($preview)->assertForbidden();
        $this->get(route('cms.media', $image->uuid))->assertForbidden();
        foreach ([['is_admin' => false], ['is_admin' => true, 'is_active' => false], ['is_admin' => true]] as $attributes) {
            $this->actingAs(User::factory()->create($attributes))->get($preview)->assertForbidden();
        }
        $this->admin();
        $this->get($preview)->assertOk()->assertSee('Beheervoorbeeld');
        $this->get(route('cms.media', $image->uuid))->assertOk()->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_referenced_media_cannot_be_deleted_and_unsafe_uploads_are_rejected(): void
    {
        $logo = $this->image();
        Organization::first()->update(['logo_media_id' => $logo->id]);
        try { $logo->delete(); $this->fail('Gekoppelde media mag niet verdwijnen.'); } catch (ValidationException) {}
        $this->assertTrue(Storage::disk('local')->exists($logo->path));
        Storage::disk('local')->put('uploads/unsafe.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $this->expectException(ValidationException::class);
        MediaAsset::create(['title' => 'Onveilig bestand', 'path' => 'uploads/unsafe.svg']);
    }

    public function test_import_is_repeatable_without_overwriting_edits_or_restoring_deleted_content(): void
    {
        $profile = SiteProfile::first();
        $profile->update(['name' => 'Mijn bewerkte profiel']);
        $project = Project::where('slug', 'globetrotr')->firstOrFail();
        $project->delete();
        $count = ContentRecord::count();
        $this->seed(PortfolioSeeder::class);
        $this->assertSame('Mijn bewerkte profiel', $profile->fresh()->name);
        $this->assertTrue($project->fresh()->trashed());
        $this->assertSame($count, ContentRecord::count());
    }

    public function test_image_upload_works_through_the_media_portal(): void
    {
        $this->admin();
        Livewire::test(\App\Filament\Resources\MediaAssetResource\Pages\CreateMediaAsset::class)
            ->fillForm(['title' => 'Logo uit het uploadformulier', 'alt_text' => 'Herkenbaar bedrijfslogo', 'path' => UploadedFile::fake()->image('logo.png', 120, 60)])
            ->call('create')->assertHasNoFormErrors();
        $media = MediaAsset::where('title', 'Logo uit het uploadformulier')->firstOrFail();
        $this->assertSame('image/webp', $media->mime_type);
        $this->assertSame(120, $media->width);
        $this->get($media->publicUrl())->assertNotFound();
    }

    public function test_project_gallery_ids_and_old_addresses_follow_publication(): void
    {
        $media = $this->image();
        $project = Project::where('slug', 'globetrotr')->firstOrFail();
        $project->update(['gallery' => [(string) $media->id], 'slug' => 'nieuw-projectadres']);
        $this->get('/projecten/globetrotr')->assertRedirect('/projecten/nieuw-projectadres')->assertStatus(301);
        $this->get($media->publicUrl())->assertOk();
        $project->update(['status' => 'draft']);
        $this->get('/projecten/globetrotr')->assertNotFound();
        $this->get($media->publicUrl())->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertDontSee('nieuw-projectadres')->assertDontSee('globetrotr');
    }

    public function test_page_visibility_and_statistic_validation_are_managed_in_the_portal(): void
    {
        ContentRecord::where('key', 'page:about')->update(['status' => 'draft']);
        $this->get('/over-mij')->assertNotFound();
        $this->get('/')->assertDontSee('href="http://localhost/over-mij"', false);
        $this->admin();
        Livewire::test(CreateContentRecord::class)->fillForm(['kind' => 'statistic', 'title' => 'Een handmatig cijfer', 'data.value' => 5])->call('create')
            ->assertHasFormErrors(['data.source', 'data.as_of']);
        Livewire::test(CreateContentRecord::class)->fillForm([
            'kind' => 'statistic', 'title' => 'Mijn gecontroleerde cijfer', 'data.value' => 7,
            'data.source' => 'Mijn eigen meting', 'data.as_of' => '2026-10-17',
        ])->call('create')->assertHasNoFormErrors();
        $statistic = ContentRecord::where('title', 'Mijn gecontroleerde cijfer')->firstOrFail();
        $this->get('/development')->assertDontSee('Mijn gecontroleerde cijfer');
        $this->get(route('cms.preview', ['type' => 'content_records', 'id' => $statistic->id]))->assertOk()->assertSee('Mijn gecontroleerde cijfer');
        $statistic->publish();
        $this->get('/development')->assertOk()->assertSee('Mijn gecontroleerde cijfer')->assertSee('Mijn eigen meting')->assertSee('2026-10-17');
        Livewire::test(CreateContentRecord::class)->fillForm(['kind' => 'page', 'key' => 'world:circuit', 'title' => 'Verkeerde soort'])
            ->call('create')->assertHasFormErrors(['key']);
    }
}
