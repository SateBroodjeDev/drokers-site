<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContentRecordResource;
use App\Filament\Resources\MediaAssetResource;
use App\Filament\Resources\OrganizationResource;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\SiteProfileResource;
use App\Models\ContentRecord;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SiteProfile;
use Filament\Widgets\Widget;

class WebsiteOverview extends Widget
{
    protected string $view = 'filament.widgets.website-overview';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = -1;

    protected function getViewData(): array
    {
        $profile = SiteProfile::first();
        return ['links' => [
            ['Profiel & vormgeving', 'Pas je foto, verhaal, kleuren en homepage aan.', $profile ? SiteProfileResource::getUrl('edit', ['record' => $profile]) : SiteProfileResource::getUrl()],
            ['Cv, pagina’s & cijfers', 'Bewerk je loopbaan, opleidingen, teksten en statistieken.', ContentRecordResource::getUrl()],
            ['Organisaties & logo’s', 'Koppel een bedrijfslogo aan functies en opleidingen.', OrganizationResource::getUrl()],
            ['Projecten', 'Werk je projecten bij en kies je eigen afbeeldingen.', ProjectResource::getUrl()],
            ['Afbeeldingen', 'Upload je foto’s, logo’s en projectbeelden.', MediaAssetResource::getUrl()],
        ], 'counts' => [
            'Gepubliceerde projecten' => Project::published()->count(),
            'Cv-onderdelen & pagina’s' => ContentRecord::count(),
            'Organisaties' => Organization::count(),
            'Concepten' => ContentRecord::where('status', 'draft')->count() + Project::where('status', 'draft')->count(),
        ]];
    }
}
