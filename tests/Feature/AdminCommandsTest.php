<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creation_prompts_for_a_password_and_does_not_seed_other_users(): void
    {
        $this->seed();
        $this->assertDatabaseCount('users', 0);

        $this->artisan('app:create-admin')
            ->expectsQuestion('Naam', 'Voorbeeldbeheerder')
            ->expectsQuestion('E-mailadres', 'owner@example.test')
            ->expectsQuestion('Wachtwoord (minimaal 12 tekens)', 'a-long-test-password')
            ->expectsQuestion('Herhaal het wachtwoord', 'a-long-test-password')
            ->assertSuccessful();

        $admin = User::query()->sole();
        $this->assertTrue($admin->is_admin);
        $this->assertTrue($admin->is_active);
        $this->assertTrue(Hash::check('a-long-test-password', $admin->password));
        $this->assertNull($admin->getAppAuthenticationSecret());
    }

    public function test_existing_admin_prevents_creating_a_second_admin(): void
    {
        User::factory()->create(['is_admin' => true]);

        $this->artisan('app:create-admin')->assertFailed();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_short_password_does_not_create_an_account(): void
    {
        $this->artisan('app:create-admin', ['--name' => 'Voorbeeld', '--email' => 'owner@example.test'])
            ->expectsQuestion('Wachtwoord (minimaal 12 tekens)', 'too-short')
            ->expectsQuestion('Herhaal het wachtwoord', 'too-short')
            ->assertFailed();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_server_recovery_invalidates_credentials_and_database_sessions(): void
    {
        config(['session.driver' => 'database']);
        $admin = User::factory()->create(['is_admin' => true, 'remember_token' => 'old-token']);
        $admin->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        $admin->saveAppAuthenticationRecoveryCodes([Hash::make('old-recovery-code')]);
        DB::table('sessions')->insert([
            'id' => 'old-session', 'user_id' => $admin->id,
            'payload' => '', 'last_activity' => time(),
        ]);

        $this->artisan('app:recover-admin', ['email' => $admin->email])
            ->expectsConfirmation('Wachtwoord vervangen, alle beheersessies intrekken en 2FA opnieuw laten instellen?', 'yes')
            ->expectsQuestion('Nieuw wachtwoord (minimaal 12 tekens)', 'a-new-long-test-password')
            ->expectsQuestion('Herhaal het wachtwoord', 'a-new-long-test-password')
            ->assertSuccessful();

        $admin->refresh();
        $this->assertTrue(Hash::check('a-new-long-test-password', $admin->password));
        $this->assertNull($admin->getAppAuthenticationSecret());
        $this->assertNull($admin->getAppAuthenticationRecoveryCodes());
        $this->assertNotSame('old-token', $admin->remember_token);
        $this->assertDatabaseCount('sessions', 0);
    }
}
