<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Support\CmsFields;
use App\Models\Project;
use BackedEnum;
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
use Illuminate\Validation\Rule;

class ProjectResource extends CmsResource
{
    protected static bool $isDiscovered = true;
    protected static ?string $model = Project::class;
    protected static ?string $modelLabel = 'project';
    protected static ?string $pluralModelLabel = 'Projecten';
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Project bewerken')->columnSpanFull()->tabs([
                Tab::make('Het project')->schema([
                    TextInput::make('name')->label('Projectnaam')->required()->maxLength(200),
                    TextInput::make('slug')->label('Adres op deze website')->required()->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')->unique(ignoreRecord: true)
                        ->rules([Rule::unique('project_redirects', 'old_slug')])->maxLength(180)
                        ->helperText('Bijvoorbeeld mijn-project. Een oud adres blijft doorverwijzen zolang het project gepubliceerd is.'),
                    TextInput::make('type')->label('Soort project')->required()->maxLength(100),
                    TextInput::make('project_status')->label('Projectstatus')->required()->maxLength(100)
                        ->helperText('Bijvoorbeeld In ontwikkeling of Eigen initiatief. Dit staat los van publiceren.'),
                    Textarea::make('summary')->label('Korte beschrijving')->required()->maxLength(2000)->rows(3)
                        ->helperText('Wat is dit project? Deze tekst staat ook op de projectkaart.'),
                    TextInput::make('role')->label('Mijn rol')->maxLength(160),
                    TextInput::make('started')->label('Gestart')->maxLength(80),
                    TextInput::make('domain')->label('Getoonde domeinnaam (optioneel)')->maxLength(180),
                    CmsFields::url('url', 'Website'),
                    CmsFields::url('repository_url', 'Repository'),
                    TagsInput::make('technologies')->label('Technieken')->helperText('Voeg alleen technieken toe die werkelijk voor dit project gebruikt zijn.'),
                ]),
                Tab::make('Het verhaal')->schema([
                    TextInput::make('headline')->label('Grote kop')->maxLength(200),
                    TextInput::make('subheadline')->label('Tweede kopregel')->maxLength(200),
                    Textarea::make('description')->label('Het verhaal achter dit project')->rows(6)->maxLength(15000)
                        ->helperText('Vertel waarom het project bestaat. Een lege regel begint een nieuwe alinea.'),
                    Repeater::make('chapters')->label('Hoofdstukken')->schema([
                        TextInput::make('title')->label('Kop')->required()->maxLength(180),
                        Textarea::make('body')->label('Toelichting')->rows(5)->maxLength(8000),
                    ])->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? 'Nieuw hoofdstuk')->maxItems(20)
                        ->addActionLabel('Hoofdstuk toevoegen')->helperText('Bijvoorbeeld het idee, jouw bijdrage, een technische keuze en de huidige stand. Sleep om de volgorde aan te passen.'),
                ]),
                Tab::make('Afbeeldingen')->schema([
                    CmsFields::image('cover_media_id', 'Projectcover'),
                    Repeater::make('gallery')->label('Afbeeldingen in het projectverhaal')
                        ->simple(CmsFields::image('media_id', 'Afbeelding')->required()->distinct())
                        ->defaultItems(0)->maxItems(24)->reorderable()->addActionLabel('Projectafbeelding toevoegen')
                        ->helperText('Sleep de afbeeldingen in de gewenste volgorde. Bijschriften en alt-teksten bewerk je in de afbeeldingenbibliotheek. Bezoekers kunnen de beelden groot bekijken.'),
                ]),
                Tab::make('Weergave')->schema([
                    Select::make('visual')->label('Illustratie wanneer er geen cover is')->options(['globe' => 'Wereldbol', 'portfolio' => 'Persoonlijke website', 'racing' => 'Simracing'])->required()->default('portfolio'),
                    Toggle::make('is_featured')->label('Uitlichten op de homepage')->default(true),
                    TextInput::make('sort_order')->label('Volgorde')->numeric()->minValue(0)->default(0),
                ]),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListProjects::route('/'), 'create' => Pages\CreateProject::route('/create'), 'edit' => Pages\EditProject::route('/{record}/edit')];
    }
}
