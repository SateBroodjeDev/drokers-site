<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/over-mij', [PortfolioController::class, 'page'])->defaults('pageKey', 'about')->name('about');
Route::get('/cv', [PortfolioController::class, 'resume'])->name('cv');
Route::get('/cv/print', [PortfolioController::class, 'printResume'])->name('cv.print');
Route::get('/projecten', [PortfolioController::class, 'projects'])->name('projects');
Route::get('/projecten/{slug}', [PortfolioController::class, 'project'])->name('projects.show');
Route::get('/development', [PortfolioController::class, 'page'])->defaults('pageKey', 'development')->name('development');
Route::get('/contact', [PortfolioController::class, 'page'])->defaults('pageKey', 'contact')->name('contact');
Route::get('/privacy', [PortfolioController::class, 'page'])->defaults('pageKey', 'privacy')->name('privacy');
Route::get('/media/{uuid}', [\App\Http\Controllers\MediaController::class, 'show'])->name('media.show');
Route::get('/sitemap.xml', [PortfolioController::class, 'sitemap'])->name('sitemap');
Route::middleware(\App\Http\Middleware\RequireContentAdmin::class)->group(function (): void {
    Route::get('/admin/voorbeeld/{type}/{id}', [PortfolioController::class, 'preview'])->whereNumber('id')->name('cms.preview');
    Route::get('/admin/media/{uuid}', [\App\Http\Controllers\MediaController::class, 'preview'])->name('cms.media');
});
