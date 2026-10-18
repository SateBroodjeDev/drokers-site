<?php

namespace Tests\Feature;

use App\Filament\Resources\ContactMessageResource\Pages\ViewContactMessage;
use App\Models\ContactMessage;
use App\Models\ContentRecord;
use App\Models\SiteProfile;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ContactMessagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(PortfolioSeeder::class);
    }

    private function payload(): array
    {
        return ['name' => 'Testbezoeker', 'email' => 'visitor@example.com', 'subject' => 'Kennismaken', 'message' => 'Ik wil graag meer weten over je project.', 'website' => ''];
    }

    public function test_contact_form_receives_a_private_message_without_smtp(): void
    {
        $this->get('/contact')->assertOk()->assertSee('name="_token"', false)->assertSee('Verstuur bericht');
        $response = $this->post('/contact', $this->payload())->assertRedirect(route('contact').'#bericht')->assertSessionHas('contact_sent', true);
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertDatabaseHas('contact_messages', ['email' => 'visitor@example.com', 'handled_at' => null]);
        $this->get('/contact')->assertSee('Bedankt voor je bericht!');
        foreach (['/', '/cv', '/contact', '/sitemap.xml'] as $path) {
            $this->get($path)->assertDontSee('visitor@example.com')->assertDontSee('Ik wil graag meer weten over je project.');
        }
    }

    public function test_invalid_and_bot_messages_are_not_saved(): void
    {
        $this->from('/contact')->post('/contact', array_replace($this->payload(), ['email' => 'invalid', 'message' => 'kort']))
            ->assertRedirect('/contact')->assertSessionHasErrors(['email', 'message']);
        $this->post('/contact', array_replace($this->payload(), ['website' => 'https://spam.example.com']))->assertSessionHasErrors('website');
        $this->post('/contact', array_replace($this->payload(), ['message' => str_repeat('x', 5001)]))->assertSessionHasErrors('message');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_endpoint_is_rate_limited(): void
    {
        for ($i = 0; $i < 3; $i++) $this->post('/contact', $this->payload())->assertRedirect();
        $this->post('/contact', $this->payload())->assertStatus(429);
        $this->assertDatabaseCount('contact_messages', 3);
    }

    public function test_hidden_page_or_profile_cannot_receive_messages(): void
    {
        $page = ContentRecord::where('key', 'page:contact')->firstOrFail();
        $page->update(['status' => 'draft']);
        $this->post('/contact', $this->payload())->assertNotFound();
        $page->update(['status' => 'published']);
        SiteProfile::first()->update(['status' => 'draft']);
        $this->post('/contact', $this->payload())->assertNotFound();
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_inbox_requires_an_active_admin_and_mfa(): void
    {
        $message = ContactMessage::create(collect($this->payload())->except('website')->all());
        $this->get('/admin/contact-messages')->assertRedirect('/admin/login');
        foreach ([['is_admin' => false], ['is_admin' => true, 'is_active' => false]] as $flags) {
            $this->actingAs(User::factory()->create($flags))->get('/admin/contact-messages')->assertForbidden();
        }
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/contact-messages')->assertRedirect('/admin/multi-factor-authentication/set-up');
        $admin->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        $this->get('/admin/contact-messages')->assertOk()->assertSee('Kennismaken');
        $this->get('/admin/contact-messages/'.$message->id)->assertOk()->assertSee('visitor@example.com');
    }

    public function test_admin_can_handle_and_delete_messages_and_script_content_is_escaped(): void
    {
        $message = ContactMessage::create(array_replace(collect($this->payload())->except('website')->all(), ['message' => '<script>alert("test")</script>']));
        $admin = User::factory()->create(['is_admin' => true]);
        $admin->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs($admin)->get('/admin/contact-messages/'.$message->id)->assertOk()
            ->assertDontSee('<script>alert("test")</script>', false)->assertSee('&lt;script&gt;', false);
        Livewire::test(ViewContactMessage::class, ['record' => $message->id])->callAction('handle');
        $this->assertNotNull($message->fresh()->handled_at);
        Livewire::test(ViewContactMessage::class, ['record' => $message->id])->callAction('delete');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_pruning_only_deletes_messages_older_than_ninety_days(): void
    {
        $old = ContactMessage::create(collect($this->payload())->except('website')->all());
        $old->forceFill(['created_at' => now()->subDays(91)])->save();
        $recent = ContactMessage::create(collect($this->payload())->except('website')->all());
        $this->artisan('model:prune', ['--model' => ContactMessage::class])->assertSuccessful();
        $this->assertModelMissing($old);
        $this->assertModelExists($recent);
    }
}
