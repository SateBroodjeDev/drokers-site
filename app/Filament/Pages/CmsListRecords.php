<?php

namespace App\Filament\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

abstract class CmsListRecords extends ListRecords
{
    protected function getHeaderActions(): array { return [CreateAction::make()->label('Toevoegen')]; }
}
