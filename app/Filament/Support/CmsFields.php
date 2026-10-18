<?php

namespace App\Filament\Support;

use App\Models\MediaAsset;
use App\Models\Organization;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

final class CmsFields
{
    public static function image(string $field, string $label): Select
    {
        return Select::make($field)->label($label)->options(fn () => MediaAsset::orderBy('title')->pluck('title', 'id'))
            ->searchable()->helperText('Upload afbeeldingen en logo’s eerst bij Afbeeldingen. Leeg laten gebruikt de standaardweergave.');
    }

    public static function organization(): Select
    {
        return Select::make('organization_id')->label('Organisatie')->options(fn () => Organization::orderBy('name')->pluck('name', 'id'))
            ->searchable()->helperText('De naam en het logo worden beheerd bij Organisaties.');
    }

    public static function url(string $field, string $label): TextInput
    {
        return TextInput::make($field)->label($label)->url()->maxLength(255)->rules(['nullable', 'regex:~^https?://~i']);
    }
}
