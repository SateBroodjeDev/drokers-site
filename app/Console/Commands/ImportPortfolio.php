<?php

namespace App\Console\Commands;

use Database\Seeders\PortfolioSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportPortfolio extends Command
{
    protected $signature = 'app:import-portfolio';
    protected $description = 'Neem de goedgekeurde portfolio-inhoud over; bestaande inhoud en wijzigingen blijven behouden';

    public function handle(): int
    {
        DB::transaction(fn () => app(PortfolioSeeder::class)->run());
        $this->info('Portfolio-inhoud geïmporteerd. Bestaande records zijn niet overschreven.');
        return self::SUCCESS;
    }
}
