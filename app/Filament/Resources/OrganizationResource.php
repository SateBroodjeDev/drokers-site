<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Support\CmsFields;
use App\Models\Organization;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class OrganizationResource extends CmsResource
{
    protected static bool $isDiscovered = true;
    protected static ?string $model = Organization::class;
    protected static ?string $modelLabel = 'organisatie';
    protected static ?string $pluralModelLabel = 'Organisaties & logo’s';
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Naam van het bedrijf / de organisatie')->required()->maxLength(200)->unique(ignoreRecord: true),
            CmsFields::url('website', 'Website'),
            CmsFields::image('logo_media_id', 'Bedrijfslogo')->helperText('Kies een eigen upload bij Afbeeldingen. Hetzelfde logo verschijnt bij alle gekoppelde functies en opleidingen. Transparante PNG of WebP werkt goed.'),
            TextInput::make('sort_order')->label('Volgorde in logo-overzicht')->numeric()->minValue(0)->default(0),
        ]);
    }

    public static function getPages(): array { return ['index' => Pages\ListOrganizations::route('/'), 'create' => Pages\CreateOrganization::route('/create'), 'edit' => Pages\EditOrganization::route('/{record}/edit')]; }
}
