<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RecoverAdmin extends Command
{
    protected $signature = 'app:recover-admin {email}';

    protected $description = 'Herstel via servertoegang een beheeraccount en trek bestaande sessies en 2FA in';

    public function handle(): int
    {
        $user = User::query()
            ->where('email', mb_strtolower(trim($this->argument('email'))))
            ->where('is_admin', true)
            ->first();

        if (! $user) {
            $this->error('Geen beheeraccount gevonden.');

            return self::FAILURE;
        }

        if (! $this->input->isInteractive() || ! $this->confirm('Wachtwoord vervangen, alle beheersessies intrekken en 2FA opnieuw laten instellen?')) {
            return self::FAILURE;
        }

        $attributes = [
            'password' => $this->secret('Nieuw wachtwoord (minimaal 12 tekens)'),
            'password_confirmation' => $this->secret('Herhaal het wachtwoord'),
        ];

        $validator = Validator::make($attributes, [
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        DB::transaction(function () use ($user, $attributes): void {
            $user->password = $attributes['password'];
            $user->remember_token = Str::random(60);
            $user->app_authentication_secret = null;
            $user->app_authentication_recovery_codes = null;
            $user->save();

            if (config('session.driver') === 'database') {
                DB::connection(config('session.connection'))
                    ->table(config('session.table', 'sessions'))
                    ->where('user_id', $user->getKey())
                    ->delete();
            }
        });

        $this->info('Toegang hersteld. Meld je opnieuw aan en stel 2FA en nieuwe herstelcodes in.');

        return self::SUCCESS;
    }
}
