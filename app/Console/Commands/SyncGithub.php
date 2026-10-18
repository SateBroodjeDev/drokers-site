<?php

namespace App\Console\Commands;

use App\Models\SiteProfile;
use App\Services\GithubStatistics;
use Illuminate\Console\Command;

class SyncGithub extends Command
{
    protected $signature = 'app:sync-github';
    protected $description = 'Haal openbare repositorycijfers op voor de ingestelde GitHub-gebruiker';

    public function handle(GithubStatistics $statistics): int
    {
        $profile = SiteProfile::where('key', 'main')->first();
        if (!$profile) { $this->error('Geen profiel ingesteld.'); return self::FAILURE; }
        $message = $statistics->sync($profile);
        $this->info($message);
        return $message === 'GitHub-statistieken bijgewerkt.' ? self::SUCCESS : self::FAILURE;
    }
}
