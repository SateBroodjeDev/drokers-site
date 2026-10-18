<?php

namespace Tests\Feature;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(PortfolioSeeder::class);
    }

    public function test_homepage_has_the_personal_identity_without_fictitious_cv_details(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Domenic Rokers')
            ->assertSee('Ontdek mijn projecten')
            ->assertSee('GlobeTrotr')
            ->assertDontSee('Test User');
    }

    public function test_healthcheck_is_available(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_registration_and_email_password_reset_are_not_exposed(): void
    {
        foreach (['/register', '/admin/register', '/admin/password-reset/request'] as $path) {
            $this->get($path)->assertNotFound();
        }
    }

    public function test_admin_login_is_not_cacheable_or_indexable(): void
    {
        $response = $this->get('/admin/login')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
    }

    public function test_pages_are_available_without_a_public_preview_banner_and_remain_non_indexable(): void
    {
        foreach (['/over-mij', '/cv', '/cv/print', '/projecten', '/projecten/globetrotr', '/projecten/domenicrokers', '/projecten/dutchunitedracing', '/development', '/contact', '/privacy'] as $path) {
            $this->get($path)->assertOk()->assertDontSee('Een eerste blik op mijn nieuwe portfolio')->assertSee('noindex, nofollow');
        }
    }

    public function test_project_search_and_technology_filter_use_the_available_factual_content(): void
    {
        $this->get('/projecten?q=globetrotr')->assertOk()->assertSee('globetrotr.nl')->assertDontSee('Mijn persoonlijke portfolio');
        $this->get('/projecten?techniek=laravel')->assertOk()->assertSee('domenicrokers.nl')->assertDontSee('globetrotr.nl');
        $this->get('/projecten?q=bestaatniet')->assertOk()->assertSee('Er is geen project');
        $this->get('/projecten?q=simracing')->assertOk()->assertSee('DutchUnitedRacing')->assertDontSee('globetrotr.nl');
        $this->get('/projecten?techniek=laravel&q=simracing')->assertOk()->assertSee('Er is geen project');
        $this->get('/projecten/onbekend')->assertNotFound();
    }

    public function test_development_preview_does_not_present_sample_data_as_measured_activity(): void
    {
        $this->get('/development')->assertOk()
            ->assertSee('geen gemeten activiteit')
            ->assertSee('Nog niet gekoppeld');
    }
}
