<?php

namespace App\Console\Commands;

use App\Models\SiteProfile;
use App\Services\GithubStatistics;
use Illuminate\Console\Command;

class ConfigureGithub extends Command
{
    protected $signature = 'app:configure-github {username} {--statistics : Schakel openbare repositorycijfers in}';
    protected $description = 'Stel de bevestigde GitHub-gebruiker in zonder overige profielinhoud te overschrijven';

    public function handle(): int
    {
        $username = trim($this->argument('username'));
        if (!GithubStatistics::validUsername($username)) { $this->error('Ongeldige GitHub-gebruikersnaam.'); return self::FAILURE; }
        $profile = SiteProfile::where('key', 'main')->first();
        if (!$profile) { $this->error('Geen profiel gevonden.'); return self::FAILURE; }
        $settings = $profile->settings ?? [];
        $settings['github_username'] = $username;
        $settings['github_statistics'] = (bool) $this->option('statistics');
        $profile->update(['settings' => $settings]);
        $this->info('GitHub-profiel ingesteld; overige inhoud en publicatiestatus zijn behouden.');
        return self::SUCCESS;
    }
}
