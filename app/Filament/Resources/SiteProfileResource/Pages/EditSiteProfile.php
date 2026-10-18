<?php

namespace App\Filament\Resources\SiteProfileResource\Pages;

use App\Filament\Pages\CmsEditRecord;
use App\Filament\Resources\SiteProfileResource;
use App\Services\GithubStatistics;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EditSiteProfile extends CmsEditRecord
{
    protected static string $resource = SiteProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [...parent::getHeaderActions(), Action::make('syncGithub')->label('GitHub bijwerken')->visible(fn () => (bool) ($this->record->settings['github_statistics'] ?? false))
            ->tooltip(function (): string {
                $username = strtolower(trim($this->record->settings['github_username'] ?? ''));
                $snapshot = \App\Models\GithubSnapshot::where('username', $username)->first();
                $status = $snapshot?->synced_at ? 'Laatste succes: '.$snapshot->synced_at->timezone('Europe/Amsterdam')->format('d-m-Y H:i') : 'Nog geen geslaagde meting.';
                return $status.($snapshot?->error_category ? ' Laatste fout: '.$snapshot->error_category : '');
            })->action(function (): void {
            $message = app(GithubStatistics::class)->sync($this->record->fresh());
            $notification = Notification::make()->title($message);
            $message === 'GitHub-statistieken bijgewerkt.' ? $notification->success() : $notification->warning();
            $notification->send();
        })];
    }
}
