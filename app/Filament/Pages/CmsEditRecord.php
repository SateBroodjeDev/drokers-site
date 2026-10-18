<?php

namespace App\Filament\Pages;

use App\Models\MediaAsset;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

abstract class CmsEditRecord extends EditRecord
{
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()->label($this->record->status === 'published' ? 'Opslaan en publiceren' : 'Opslaan');
    }

    protected function getHeaderActions(): array
    {
        if ($this->record instanceof MediaAsset) return [
            Action::make('imagePreview')->label('Afbeelding bekijken')->url(fn () => route('cms.media', $this->record->uuid))->openUrlInNewTab(),
            DeleteAction::make()->disabled(fn () => $this->record->isReferenced())
                ->tooltip(fn () => $this->record->isReferenced() ? 'Verwijder eerst de koppelingen, ook bij inhoud in de prullenbak.' : null),
        ];

        return [
            Action::make('preview')->label('Voorbeeld bekijken')->url(fn () => route('cms.preview', ['type' => $this->record->getTable(), 'id' => $this->record->id]))->openUrlInNewTab(),
            Action::make('publish')->label('Publiceren')->color('success')->visible(fn () => $this->record->status !== 'published')->action(function (): void {
                $this->save();
                if ($this->getErrorBag()->isNotEmpty()) return;
                $this->record->publish();
                Notification::make()->title('Gepubliceerd')->success()->send();
            }),
            Action::make('unpublish')->label('Terug naar concept')->color('warning')->requiresConfirmation()->visible(fn () => $this->record->status === 'published')->action(function (): void {
                $this->record->update(['status' => 'draft']);
                Notification::make()->title('Niet meer publiek zichtbaar')->success()->send();
            }),
            Action::make('archive')->label('Archiveren')->color('gray')->requiresConfirmation()->visible(fn () => $this->record->status !== 'archived')->action(fn () => $this->record->update(['status' => 'archived'])),
            DeleteAction::make(),
        ];
    }
}
