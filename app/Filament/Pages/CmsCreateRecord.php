<?php

namespace App\Filament\Pages;

use App\Models\MediaAsset;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

abstract class CmsCreateRecord extends CreateRecord
{
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->label(static::getResource()::getModel() === MediaAsset::class ? 'Afbeelding opslaan' : 'Als concept opslaan');
    }

    protected function getRedirectUrl(): string { return static::getResource()::getUrl('edit', ['record' => $this->record]); }
}
