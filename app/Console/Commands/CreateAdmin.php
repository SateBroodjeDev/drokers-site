<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin {--name=} {--email=}';

    protected $description = 'Maak interactief het eerste en enige beheeraccount aan';

    public function handle(): int
    {
        if (User::query()->where('is_admin', true)->exists()) {
            $this->error('Er bestaat al een beheeraccount. Gebruik zo nodig app:recover-admin.');

            return self::FAILURE;
        }

        if (! $this->input->isInteractive()) {
            $this->error('Maak het beheeraccount interactief aan; wachtwoorden horen niet in commandoregelopties.');

            return self::FAILURE;
        }

        $attributes = [
            'name' => $this->option('name') ?: $this->ask('Naam'),
            'email' => mb_strtolower(trim($this->option('email') ?: $this->ask('E-mailadres') ?? '')),
            'password' => $this->secret('Wachtwoord (minimaal 12 tekens)'),
            'password_confirmation' => $this->secret('Herhaal het wachtwoord'),
        ];

        $validator = Validator::make($attributes, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $user = new User;
        $user->name = $attributes['name'];
        $user->email = $attributes['email'];
        $user->password = $attributes['password'];
        $user->is_admin = true;
        $user->is_active = true;
        $user->save();

        $this->info('Beheeraccount aangemaakt. Meld je aan op /admin en stel je authenticator en herstelcodes in.');

        return self::SUCCESS;
    }
}
