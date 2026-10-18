<?php

namespace Database\Seeders;

use App\Models\ContentRecord;
use App\Models\MediaAsset;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SiteProfile;
use App\Support\PortfolioDesign;
use App\Support\ResumeContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/** Imports the previously approved prototype once. Existing edits are never overwritten. */
class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $published = ['status' => 'published', 'published_at' => now()];
        $source = collect([storage_path('app/private/import-source/domenic-rokers.webp'), public_path('images/domenic-rokers.webp')])->first(fn ($path) => is_file($path));
        $photo = MediaAsset::where('path', 'portfolio/initial-portrait.webp')->first();
        if (! $photo && $source) {
            Storage::disk('local')->put('portfolio/initial-portrait.webp', file_get_contents($source));
            $photo = MediaAsset::create(['title' => 'Domenic — portret uit eigen cv', 'alt_text' => 'Portret van Domenic Rokers', 'path' => 'portfolio/initial-portrait.webp']);
        }
        $profile = ResumeContent::profile();
        SiteProfile::withTrashed()->firstOrCreate(['key' => 'main'], array_merge($published, [
            'name' => $profile['name'], 'headline' => $profile['headline'], 'intro' => $profile['intro'], 'photo_media_id' => $photo?->id,
            'biography' => "Ik sta graag voor anderen klaar. In de meldkamer bij NS betekent dat overzicht houden, prioriteiten stellen en helder communiceren als het spannend wordt.\n\nErvaring in de gastvrijheid deed ik onder meer op bij de Efteling. Via reizigersservice, technische ondersteuning en evenementenbeveiliging groeide mijn aandacht voor mensen én veiligheid. Als hoofdconducteur deed ik ervaring op in de praktijk van het spoor; sinds maart 2026 werk ik als meldkamercentralist.\n\nNaast mijn werk ben ik vrijwillig marshal op Circuit Zandvoort. Formule 1 en simracing zijn grote passies. Ook technologie en luchtvaart houden me bezig: ik programmeer, ontwikkel digitale projecten en probeer graag iets nieuws.\n\nIn december 2023 begon ik DutchUnitedRacing. Met dit simracinginitiatief wil ik een community opbouwen waar raceplezier, eerlijkheid en samen leren centraal staan.\n\nBehulpzaam, verantwoordelijk en ondernemend passen bij mij. Elke dag hetzelfde wat minder. Geef mij afwisseling, een uitdaging en ruimte voor een beetje humor.",
            'settings' => [
                'theme' => 'paddock', 'typography' => 'technical', 'motion' => true, 'show_world' => true, 'show_development' => true, 'show_organizations' => true,
                'hero_heading' => "Rust in de actie.\nAan het bouwen.\nAltijd in beweging.",
                'hero_kicker' => 'Mensen. Motorsport. Technologie.', 'hero_badge' => 'Marshal & maker', 'hero_note' => 'Stilzitten? Niet mijn ding. Een beetje humor wel.',
                'world_heading' => "Twee werelden.\nEén Domenic.", 'world_intro' => 'Van de baan in Zandvoort tot een nieuw digitaal project. Ik voel me thuis waar iets gebeurt.',
                'interests' => $profile['interests'], 'traits' => ['Behulpzaam', 'Verantwoordelijk', 'Ondernemend'], 'links' => [],
                'footer_heading' => 'Laten we kennismaken.', 'footer_line' => 'Met aandacht voor mensen, techniek en de volgende bocht.',
                'career' => [
                    ['year' => '2020', 'label' => 'Gastvrijheid', 'title' => 'Aandacht maakt het verschil.', 'body' => 'Bij de Efteling leerde ik gasten een fijne beleving bieden. Bij Qarin en Coolblue ging het opnieuw om mensen helpen en oplossingen vinden.'],
                    ['year' => '2022', 'label' => 'Veiligheid', 'title' => 'Rust brengen in de actie.', 'body' => 'Evenementenbeveiliging, vrijwilligerswerk als marshal en het werk als hoofdconducteur: situaties inschatten en verantwoordelijkheid nemen.'],
                    ['year' => '2026', 'label' => 'Coördinatie', 'title' => 'Het overzicht bewaren.', 'body' => 'Als meldkamercentralist bij NS komen ervaring, communicatie en veiligheid samen. Meerdere meldingen, duidelijke prioriteiten en samenwerken.'],
                ],
            ],
        ]));

        foreach (['work' => ResumeContent::experiences(), 'volunteer' => ResumeContent::volunteering(), 'initiative' => ResumeContent::initiatives()] as $kind => $entries) {
            foreach ($entries as $index => $entry) {
                $org = Organization::withTrashed()->firstOrCreate(['name' => $entry['organization']], $published + ['sort_order' => $index]);
                ContentRecord::withTrashed()->firstOrCreate(['key' => $kind.':'.$index], $published + [
                    'kind' => $kind, 'title' => $entry['role'], 'organization_id' => $org->id, 'location' => $entry['location'],
                    'period' => $entry['period'], 'is_current' => $entry['current'], 'summary' => $entry['summary'], 'sort_order' => $index,
                    'data' => ['arrangement' => $entry['arrangement'], 'responsibilities' => $entry['responsibilities']],
                ]);
            }
        }
        foreach (['education' => ResumeContent::education(), 'training' => ResumeContent::training()] as $kind => $entries) {
            foreach ($entries as $index => $entry) {
                $org = $entry['issuer'] ? Organization::withTrashed()->firstOrCreate(['name' => $entry['issuer']], $published) : null;
                ContentRecord::withTrashed()->firstOrCreate(['key' => $kind.':'.$index], $published + ['kind' => $kind, 'title' => $entry['title'], 'organization_id' => $org?->id, 'period' => $entry['period'], 'sort_order' => $index, 'data' => ['qualification_status' => $entry['status']]]);
            }
        }
        foreach (ResumeContent::skills() as $index => $group) {
            ContentRecord::withTrashed()->firstOrCreate(['key' => 'skill:'.$index], $published + ['kind' => 'skill', 'title' => $group['name'], 'sort_order' => $index, 'data' => ['items' => $group['items'], 'icon' => $group['icon']]]);
        }
        foreach (ResumeContent::languages() as $index => $language) {
            ContentRecord::withTrashed()->firstOrCreate(['key' => 'language:'.$index], $published + ['kind' => 'language', 'title' => $language['name'], 'sort_order' => $index, 'data' => ['code' => $language['code'], 'level' => $language['level']]]);
        }

        $pages = [
            'home' => ['Home', '01 / Persoonlijk portfolio', "Waar ik aan werk.", 'Een plek voor mijn werk, projecten en de persoon erachter.'],
            'about' => ['Over mij', '02 / De persoon achter het werk', "Een rustig hoofd.\nEen hart voor actie.", 'Ik help graag anderen, neem verantwoordelijkheid en maak graag ruimte voor een nieuw idee. Op het circuit én daarbuiten.'],
            'cv' => ['Mijn cv', '03 / Ervaring & achtergrond', "Mijn verhaal,\nin hoofdstukken.", 'Van mensen welkom heten tot overzicht houden wanneer het spannend wordt. Dit neem ik mee.'],
            'projects' => ['Projecten', '01 / Van idee naar project', "Van idee naar\niets tastbaars.", 'Projecten waar ik aan werk, keuzes onderweg en de verhalen erachter. Dit overzicht groeit met me mee.'],
            'development' => ['Development', '04 / Onder de motorkap', "Van nieuwsgierigheid\nnaar code.", 'De techniek van deze website, projecten in mijn portfolio en straks een blik op mijn developmentactiviteit.'],
            'contact' => ['Contact', '05 / Even kennismaken', "Een goed gesprek\nbegint met hallo.", 'Nieuwsgierig naar een project, mijn achtergrond of een idee? Laten we kennismaken.'],
            'privacy' => ['Privacy', 'Helder over deze website', "Privacy,\nin gewone taal.", 'Deze lokale ontwerpversie wordt vóór livegang gecontroleerd op de uiteindelijke hosting en functies.'],
        ];
        foreach ($pages as $key => [$title, $kicker, $heading, $intro]) {
            $sections = $key === 'privacy' ? [
                ['title' => 'Werking', 'body' => 'De applicatie gebruikt sessiecookies voor de werking van de website en de afgeschermde beheeromgeving. Er is geen bezoekersaccount of openbaar contactformulier.'],
                ['title' => 'Beelden en lettertypen', 'body' => 'Lettertypen en afbeeldingen worden vanaf deze website geladen. Mijn portret is afkomstig uit mijn eigen cv. Bedrijfslogo’s worden gebruikt ter herkenning van organisaties in mijn achtergrond.'],
                ['title' => 'Statistieken', 'body' => 'Handmatige cijfers hebben een bron en peildatum. GitHub is nog niet gekoppeld; de kalender is expliciet een ontwerpvoorbeeld.'],
            ] : [];
            ContentRecord::withTrashed()->firstOrCreate(['key' => 'page:'.$key], $published + ['kind' => 'page', 'title' => $title, 'summary' => $intro, 'data' => ['kicker' => $kicker, 'heading' => $heading, 'sections' => $sections]]);
        }
        foreach ([
            ['circuit', 'Een rustig hoofd. Een hart voor racen.', 'Als marshal op Circuit Zandvoort sta ik graag voor anderen klaar en blijf ik rustig als het spannend wordt. Formule 1 en simracing zijn grote passies.', 'Marshal · Formule 1 · Simracing'],
            ['code', 'Van “zou dat kunnen?” naar zelf bouwen.', 'Programmeren, digitale projecten ontwikkelen en nieuwe technologie uitproberen. Ik werk aan Globetrotr en dit portfolio. Met DutchUnitedRacing komen simracing en het bouwen van een community samen.', 'Technologie · Eigen projecten · Experimenteren'],
            ['character', 'Vooruit met aandacht voor anderen.', 'Behulpzaam, verantwoordelijk en ondernemend. Geef mij afwisseling, een uitdaging en een beetje actie. En ruimte voor humor, want het hoeft niet altijd zo serieus.', 'Stilzitten staat niet op de planning.'],
        ] as $index => [$key, $title, $summary, $note]) {
            ContentRecord::withTrashed()->firstOrCreate(['key' => 'world:'.$key], $published + ['kind' => 'world', 'title' => $title, 'summary' => $summary, 'sort_order' => $index, 'data' => ['note' => $note]]);
        }

        foreach (PortfolioDesign::projects() as $index => $project) {
            $attributes = $project;
            unset($attributes['number'], $attributes['status']);
            Project::withTrashed()->firstOrCreate(['slug' => $project['slug']], $published + $attributes + [
                'project_status' => $project['status'], 'sort_order' => $index, 'is_featured' => true,
            ]);
        }

        $this->callSilent(OrganizationLogoSeeder::class);
    }
}
