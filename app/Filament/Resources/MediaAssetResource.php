<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaAssetResource\Pages;
use App\Models\MediaAsset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Filament\Support\CmsFields;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaAssetResource extends CmsResource
{
    protected static bool $isDiscovered = true;
    protected static ?string $model = MediaAsset::class;
    protected static ?string $modelLabel = 'afbeelding';
    protected static ?string $pluralModelLabel = 'Afbeeldingen';
    protected static ?string $recordTitleAttribute = 'title';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Herkenbare naam')->required()->maxLength(200),
            TextInput::make('alt_text')->label('Beschrijving voor bezoekers die het beeld niet zien')->maxLength(250),
            Textarea::make('caption')->label('Bijschrift bij projectafbeeldingen')->maxLength(1000)->rows(3)
                ->helperText('Een korte toelichting bij een screenshot of projectbeeld. Dit verschijnt onder de afbeelding in de projectgalerij.'),
            Select::make('background')->label('Achtergrond bij gebruik als logo')->options(['light' => 'Licht — voor donkere of gekleurde logo’s', 'dark' => 'Donkerblauw — voor witte logo’s'])->default('light')->required(),
            TextInput::make('source_label')->label('Bron / maker (optioneel)')->maxLength(200),
            CmsFields::url('source_url', 'Bronlink (voor je eigen administratie)')->maxLength(2000),
            FileUpload::make('path')->label('Afbeelding of bedrijfslogo')->disk('local')->directory('uploads')->visibility('private')
                ->getUploadedFileUsing(function (string $file): ?array {
                    $disk = \Illuminate\Support\Facades\Storage::disk('local');
                    if (! $disk->exists($file)) return null;
                    return ['name' => basename($file), 'size' => $disk->size($file), 'type' => $disk->mimeType($file), 'url' => null];
                })
                ->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])->maxSize(5120)->required()
                ->previewable(false)->openable(false)->downloadable(false)
                ->helperText('JPEG, PNG of WebP, maximaal 5 MB en 16 megapixels. Transparantie blijft behouden. SVG is niet toegestaan. Kies deze afbeelding daarna bij een organisatie, project of je profiel.'),
        ]);
    }

    public static function getPages(): array { return ['index' => Pages\ListMediaAssets::route('/'), 'create' => Pages\CreateMediaAsset::route('/create'), 'edit' => Pages\EditMediaAsset::route('/{record}/edit')]; }

    public static function table(Table $table): Table
    {
        return parent::table($table)->columns([
            ImageColumn::make('preview')->label('Beeld')->state(fn (MediaAsset $record) => route('cms.media', $record->uuid))->height(44),
            TextColumn::make('title')->label('Naam')->searchable(),
            TextColumn::make('alt_text')->label('Beschrijving')->wrap(),
            TextColumn::make('updated_at')->label('Bijgewerkt')->dateTime('d-m-Y H:i'),
        ]);
    }
}
