<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('contact', fn (Request $request) => [
            Limit::perMinute(3)->by('contact-minute:'.hash('sha256', $request->ip() ?? 'unknown')),
            Limit::perDay(20)->by('contact-day:'.hash('sha256', $request->ip() ?? 'unknown')),
        ]);
    }
}
