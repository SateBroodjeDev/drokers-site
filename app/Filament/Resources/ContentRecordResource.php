<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentRecordResource\Pages;
use App\Filament\Support\CmsFields;
use App\Models\ContentRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use BackedEnum;

class ContentRecordResource extends CmsResource
{
    protected static bool $isDiscovered = true;
    protected static ?string $model = ContentRecord::class;
    protected static ?string $modelLabel = 'onderdeel';
    protected static ?string $pluralModelLabel = 'Cv, pagina’s & cijfers';
    protected static ?string $recordTitleAttribute = 'title';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?int $navigationSort = 2;

    private static function contentKeys(?string $kind): array
    {
        return $kind === 'world' ? [
            'world:circuit' => 'Persoonlijk: circuit', 'world:code' => 'Persoonlijk: code', 'world:character' => 'Persoonlijk: karakter',
        ] : [
            'page:home' => 'Homepage', 'page:about' => 'Over mij', 'page:cv' => 'Cv', 'page:projects' => 'Projecten',
            'page:development' => 'Development', 'page:contact' => 'Contact', 'page:privacy' => 'Privacy',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Onderdeel')->columnSpanFull()->schema([
                Select::make('kind')->label('Soort')->options(ContentRecord::KINDS)->required()->live()->in(array_keys(ContentRecord::KINDS)),
                Select::make('key')->label('Pagina of verhaal')->options(fn (Get $get) => self::contentKeys($get('kind')))
                    ->in(fn (Get $get) => array_keys(self::contentKeys($get('kind'))))
                    ->unique(ignoreRecord: true)->visible(fn (Get $get) => in_array($get('kind'), ['page', 'world']))->required(fn (Get $get) => in_array($get('kind'), ['page', 'world'])),
                TextInput::make('title')->label('Titel / functie / opleiding')->required()->maxLength(230),
                Textarea::make('summary')->label('Beschrijving / introductie')->rows(5)->maxLength(15000),
                TextInput::make('sort_order')->label('Volgorde')->numeric()->minValue(0)->default(0),
            ]),
            Section::make('Ervaring & opleiding')->columnSpanFull()->visible(fn (Get $get) => in_array($get('kind'), ['work', 'volunteer', 'initiative', 'education', 'training']))->schema([
                CmsFields::organization(),
                TextInput::make('location')->label('Locatie')->maxLength(180),
                TextInput::make('period')->label('Periode')->maxLength(160)->helperText('Bijvoorbeeld: Maart 2026 — heden. Neem de periode uit je leidende cv over.'),
                Toggle::make('is_current')->label('Huidige rol'),
                TextInput::make('data.arrangement')->label('Dienstverband / label')->maxLength(100),
                TagsInput::make('data.responsibilities')->label('Werkzaamheden')->helperText('Voeg elke werkzaamheid als apart punt toe.'),
                TextInput::make('data.qualification_status')->label('Diploma- of certificaatstatus')->maxLength(100)->helperText('Bijvoorbeeld Diploma behaald. Laat onbekende informatie leeg.'),
            ]),
            Section::make('Vaardigheden & talen')->columnSpanFull()->visible(fn (Get $get) => in_array($get('kind'), ['skill', 'language']))->schema([
                TagsInput::make('data.items')->label('Vaardigheden in deze groep'),
                Select::make('data.icon')->label('Icoon')->options(['code' => 'Code', 'briefcase' => 'Werk', 'spark' => 'Persoonlijk'])->default('spark'),
                TextInput::make('data.code')->label('Taalafkorting')->maxLength(5),
                TextInput::make('data.level')->label('Taalniveau')->maxLength(150),
            ]),
            Section::make('Pagina & persoonlijk verhaal')->columnSpanFull()->visible(fn (Get $get) => in_array($get('kind'), ['page', 'world']))->schema([
                Textarea::make('data.heading')->label('Grote kop')->visible(fn (Get $get) => $get('kind') === 'page')->rows(2)->maxLength(300)->helperText('Gebruik een nieuwe regel voor een nieuwe kopregel.'),
                TextInput::make('data.kicker')->label('Kleine tekst boven de kop')->visible(fn (Get $get) => $get('kind') === 'page')->maxLength(160),
                TextInput::make('data.note')->label('Kort label onder het verhaal')->visible(fn (Get $get) => $get('kind') === 'world')->maxLength(200),
                TextInput::make('data.card_title')->label('Korte titel op de interessekaart')->visible(fn (Get $get) => $get('kind') === 'world')->maxLength(100)->helperText('Leeg gebruikt Autosport, Technologie of Karakter, afhankelijk van het verhaal.'),
                CmsFields::image('image_media_id', 'Eigen pagina-afbeelding'),
                Repeater::make('data.sections')->label('Tekstonderdelen')->visible(fn (Get $get) => $get('kind') === 'page')->schema([TextInput::make('title')->label('Kop')->maxLength(180), Textarea::make('body')->label('Tekst')->maxLength(8000)])->collapsible()->maxItems(20),
            ]),
            Section::make('Statistiek met bron')->columnSpanFull()->visible(fn (Get $get) => $get('kind') === 'statistic')->schema([
                TextInput::make('data.value')->label('Waarde')->numeric()->required(fn (Get $get) => $get('kind') === 'statistic'),
                TextInput::make('data.unit')->label('Eenheid')->maxLength(40),
                TextInput::make('data.source')->label('Bronvermelding')->maxLength(200)->required(fn (Get $get) => $get('kind') === 'statistic'),
                CmsFields::url('data.source_url', 'Bronlink (optioneel)'),
                DatePicker::make('data.as_of')->label('Peildatum')->required(fn (Get $get) => $get('kind') === 'statistic'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)->pushFilters([SelectFilter::make('kind')->label('Soort onderdeel')->options(ContentRecord::KINDS)]);
    }

    public static function getPages(): array { return ['index' => Pages\ListContentRecords::route('/'), 'create' => Pages\CreateContentRecord::route('/create'), 'edit' => Pages\EditContentRecord::route('/{record}/edit')]; }
}
