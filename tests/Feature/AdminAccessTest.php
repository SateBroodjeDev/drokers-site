<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Auth\Pages\Login;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_regular_and_inactive_users_cannot_enter_admin(): void
    {
        foreach ([['is_admin' => false], ['is_admin' => true, 'is_active' => false]] as $attributes) {
            $user = User::factory()->create($attributes);

            $this->actingAs($user)->get('/admin')->assertForbidden();
        }
    }

    public function test_admin_without_totp_is_required_to_complete_setup(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get('/admin')
            ->assertRedirect('/admin/multi-factor-authentication/set-up');

        $this->get('/admin/multi-factor-authentication/set-up')->assertOk();
    }

    public function test_admin_with_totp_can_enter_an_authenticated_session(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $admin->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_permission_flags_cannot_be_mass_assigned(): void
    {
        $user = new User;
        $user->fill(['name' => 'Voorbeeld', 'is_admin' => true, 'is_active' => false]);

        $this->assertFalse($user->is_admin);
        $this->assertTrue($user->is_active);
    }

    public function test_mfa_secrets_are_encrypted_and_hidden_from_serialization(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $admin->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        $admin->saveAppAuthenticationRecoveryCodes([Hash::make('one-use-recovery-code')]);

        $stored = DB::table('users')->find($admin->id);
        $this->assertNotSame('JBSWY3DPEHPK3PXP', $stored->app_authentication_secret);
        $this->assertStringNotContainsString('one-use-recovery-code', $stored->app_authentication_recovery_codes);
        $this->assertArrayNotHasKey('app_authentication_secret', $admin->toArray());
        $this->assertArrayNotHasKey('app_authentication_recovery_codes', $admin->toArray());
    }

    public function test_recovery_code_can_only_be_used_once(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $provider = AppAuthentication::make()->recoverable();
        $provider->saveRecoveryCodes($admin, ['one-use-recovery-code', 'another-recovery-code']);

        $this->assertTrue($provider->verifyRecoveryCode('one-use-recovery-code', $admin));
        $this->assertFalse($provider->verifyRecoveryCode('one-use-recovery-code', $admin));
        $this->assertCount(1, $admin->fresh()->getAppAuthenticationRecoveryCodes());
    }

    public function test_login_requires_a_valid_totp_challenge(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $admin->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $login = Livewire::test(Login::class)
            ->fillForm(['email' => $admin->email, 'password' => 'password'])
            ->call('authenticate');

        $this->assertGuest();
        $login->assertHasNoFormErrors();
        $provider = AppAuthentication::make();
        $login->fillForm([
            'multiFactor' => ['app' => ['code' => $provider->getCurrentCode($admin)]],
        ])->call('authenticate')->assertHasNoFormErrors();

        $this->assertAuthenticatedAs($admin);
    }

    public function test_valid_password_does_not_admit_a_regular_or_inactive_user(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        foreach ([['is_admin' => false], ['is_admin' => true, 'is_active' => false]] as $attributes) {
            $user = User::factory()->create($attributes);

            Livewire::test(Login::class)
                ->fillForm(['email' => $user->email, 'password' => 'password'])
                ->call('authenticate')
                ->assertHasFormErrors(['email']);

            $this->assertGuest();
        }
    }
}
