<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteProfileResource\Pages;
use App\Filament\Support\CmsFields;
use App\Models\SiteProfile;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class SiteProfileResource extends CmsResource
{
    protected static bool $isDiscovered = true;
    protected static ?string $model = SiteProfile::class;
    protected static ?string $modelLabel = 'profiel en vormgeving';
    protected static ?string $pluralModelLabel = 'Profiel & vormgeving';
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;
    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool { return parent::canCreate() && ! SiteProfile::withTrashed()->where('key', 'main')->exists(); }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('key')->default('main'),
            Tabs::make('Instellingen')->columnSpanFull()->tabs([
                Tab::make('Mijn profiel')->schema([
                    TextInput::make('name')->label('Naam')->required()->maxLength(100),
                    TextInput::make('headline')->label('Functietitel / ondertitel')->maxLength(200),
                    Textarea::make('intro')->label('Korte introductie')->rows(4)->maxLength(3000),
                    Textarea::make('biography')->label('Mijn verhaal')->rows(10)->maxLength(15000)->helperText('Een lege regel begint een nieuwe alinea.'),
                    CmsFields::image('photo_media_id', 'Portretfoto'),
                    TagsInput::make('settings.interests')->label('Interesses'),
                    TagsInput::make('settings.traits')->label('Eigenschappen'),
                ]),
                Tab::make('Homepage')->schema([
                    TextInput::make('settings.welcome')->label('Begroeting boven mijn naam')->placeholder('Hoi, ik ben')->maxLength(100)->helperText('Je naam, functietitel en introductie komen uit Mijn profiel.'),
                    TextInput::make('settings.career_heading')->label('Kop bij werkervaring')->placeholder('Mijn achtergrond.')->maxLength(160),
                    TextInput::make('settings.initiatives_heading')->label('Kop bij eigen initiatieven')->placeholder('Mijn eigen initiatieven.')->maxLength(160),
                    TextInput::make('settings.interests_heading')->label('Kop boven interessekaarten')->placeholder('Wat mij bezighoudt.')->maxLength(160),
                    TextInput::make('settings.hero_note')->label('Persoonlijke noot')->maxLength(200),
                    CmsFields::image('hero_media_id', 'Eigen hoofdafbeelding (optioneel)'),
                    TextInput::make('settings.world_heading')->label('Kop bij het persoonlijke verhaal')->maxLength(200),
                    Textarea::make('settings.world_intro')->label('Intro bij het persoonlijke verhaal')->maxLength(1500),
                    Repeater::make('settings.career')->label('Haltes in mijn verhaal')->schema([
                        TextInput::make('year')->label('Jaar / periode')->maxLength(60),
                        TextInput::make('label')->label('Thema')->maxLength(100),
                        TextInput::make('title')->label('Kop')->required()->maxLength(160),
                        Textarea::make('body')->label('Toelichting')->maxLength(2000),
                    ])->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? 'Nieuwe halte')->maxItems(12),
                ]),
                Tab::make('Kleuren & stijl')->schema([
                    Select::make('settings.appearance')->label('Lichte of donkere uitstraling')->options(['night' => 'Nacht — donker en sfeervol', 'light' => 'Licht — rustig en redactioneel'])->default('night')->required()
                        ->afterStateHydrated(fn (Select $component, ?string $state) => $component->state(in_array($state, ['night', 'light'], true) ? $state : 'night'))
                        ->helperText('Zonder eigen achtergrond- of tekstkleur gebruikt elke uitstraling passende basiskleuren.'),
                    Select::make('settings.theme')->label('Kleurenschema')->options(['paddock' => 'Paddock — donkerblauw & oranje', 'graphite' => 'Graphite — zwart & koraal', 'electric' => 'Electric — nachtblauw & helderblauw'])->required(),
                    Select::make('settings.typography')->label('Koppen')->options(['technical' => 'Strak & technisch', 'editorial' => 'Redactioneel met cursieve accenten'])->default('technical')->required(),
                    ColorPicker::make('settings.accent')->label('Eigen accentkleur (optioneel)')->regex('/^#[0-9a-fA-F]{6}$/')->helperText('Leeg laten gebruikt het kleurenschema. Kies kleuren met voldoende contrast.'),
                    ColorPicker::make('settings.background')->label('Eigen lichte achtergrond (optioneel)')->regex('/^#[0-9a-fA-F]{6}$/'),
                    ColorPicker::make('settings.ink')->label('Eigen tekstkleur (optioneel)')->regex('/^#[0-9a-fA-F]{6}$/'),
                    CmsFields::image('brand_media_id', 'Eigen beeldmerk / logo'),
                    Toggle::make('settings.motion')->label('Speelse bewegingen inschakelen')->default(true),
                    Toggle::make('settings.show_world')->label('Interactieve persoonlijke verhalen tonen')->default(true),
                    Toggle::make('settings.show_development')->label('Development op home en in de footer tonen')->default(true),
                    Toggle::make('settings.show_organizations')->label('Organisatielogo’s bij werkervaring op home tonen')->default(true),
                ]),
                Tab::make('Contact & footer')->schema([
                    TextInput::make('settings.github_username')->label('GitHub-gebruikersnaam')->maxLength(39)->regex('/\A(?!.*--)[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,37}[a-zA-Z0-9])?\z/')->helperText('Alleen je gebruikersnaam. Je profiel wordt op contact en development gekoppeld.'),
                    Toggle::make('settings.github_statistics')->label('Openbare GitHub-statistieken publiceren')->default(false)->helperText('Sla eerst op en kies daarna GitHub bijwerken. Alleen openbare eigen repositories, zonder forks en archieven.'),
                    TextInput::make('settings.contact_heading')->label('Kop boven de contactmogelijkheden')->placeholder('Neem contact op.')->maxLength(160),
                    Textarea::make('settings.contact_intro')->label('Intro boven de contactmogelijkheden')->placeholder('Voor een kennismaking, een professionele vraag of een gesprek over wat ik doe.')->maxLength(1000),
                    Repeater::make('settings.links')->label('Openbare contactlinks')->schema([
                        TextInput::make('label')->label('Label')->required()->maxLength(80),
                        TextInput::make('description')->label('Korte toelichting op de contactpagina (optioneel)')->maxLength(200),
                        TextInput::make('url')->label('Link')->required()->maxLength(255)->regex('~^(https?://|mailto:[^\s@]+@)~i'),
                    ])->helperText('Gebruik https:// voor profielpagina’s of mailto: voor een bewust gekozen openbaar e-mailadres.')->maxItems(12),
                    TextInput::make('settings.footer_line')->label('Tekst onder mijn naam')->maxLength(250),
                    TextInput::make('settings.footer_heading')->label('Uitnodiging in de footer')->maxLength(150),
                ]),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListSiteProfiles::route('/'), 'create' => Pages\CreateSiteProfile::route('/create'), 'edit' => Pages\EditSiteProfile::route('/{record}/edit')];
    }
}
