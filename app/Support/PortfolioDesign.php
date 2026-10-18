<?php

namespace App\Support;

/**
 * Selected public project facts, including the owner's confirmed project descriptions.
 * Import source only; public pages read published CMS records.
 */
final class PortfolioDesign
{
    public static function projects(): array
    {
        return [
            [
                'slug' => 'globetrotr',
                'name' => 'GlobeTrotr',
                'domain' => 'globetrotr.nl',
                'type' => 'Reisplatform',
                'visual' => 'globe',
                'number' => '01',
                'status' => 'In ontwikkeling',
                'headline' => 'Je hele reis.',
                'subheadline' => 'Eén overzicht.',
                'role' => 'Oprichter & Full-stack Developer',
                'started' => null,
                'summary' => 'Een online platform in ontwikkeling om je volledige reis te plannen en beheren: routes, accommodaties, activiteiten en budgetten, ook samen met je reisgenoten.',
                'description' => "Met GlobeTrotr ontwikkel ik een gebruiksvriendelijk reisplatform. Het doel is om alle onderdelen van een reis op één plek te kunnen plannen en beheren, van de route en overnachtingen tot activiteiten en budgetten.\n\nIk ben de oprichter en ontwikkel het platform zelfstandig. Het concept, het UX/UI-design, de technische ontwikkeling en de inrichting van de database vallen onder mijn verantwoordelijkheid. Het project is nog in ontwikkeling.",
                'technologies' => ['HTML', 'CSS', 'JavaScript', 'Supabase', 'Leaflet.js', 'API-integraties'],
                'chapters' => [
                    ['title' => 'De reis als geheel', 'body' => 'Routes, accommodaties, activiteiten en budgetten hangen met elkaar samen. Met GlobeTrotr wil ik reizigers een overzichtelijke plek geven om die onderdelen te plannen en bij te houden. Gezamenlijke reisplanning hoort bij dat doel.'],
                    ['title' => 'Van concept naar interface', 'body' => 'Ik werk het idee zelf uit en verzorg het UX/UI-design en de technische ontwikkeling. HTML, CSS en JavaScript vormen de basis van de interface.'],
                    ['title' => 'Kaarten en reisgegevens', 'body' => 'Ik ontwikkel functionaliteiten voor gebruikersauthenticatie, interactieve kaarten en het synchroniseren van reisgegevens. Supabase wordt gebruikt voor de database, authenticatie en Row Level Security. Leaflet.js verzorgt de kaarten; API-integraties verbinden aanvullende gegevens.'],
                    ['title' => 'Verder bouwen', 'body' => 'GlobeTrotr is in ontwikkeling. De huidige focus ligt op het uitwerken van het platform en de samenhang tussen de reisfuncties.'],
                ],
                'url' => 'https://globetrotr.nl',
            ],
            [
                'slug' => 'domenicrokers',
                'name' => 'Mijn persoonlijke portfolio',
                'domain' => 'domenicrokers.nl',
                'type' => 'Persoonlijke website',
                'visual' => 'portfolio',
                'number' => '02',
                'status' => 'In ontwikkeling',
                'headline' => 'Een eigen plek.',
                'subheadline' => 'Helemaal van mij.',
                'role' => 'Eigen portfolio',
                'started' => null,
                'summary' => 'Mijn ervaring, projecten en interesses op één plek. Met een eigen beheerportaal voor inhoud, kleuren, afbeeldingen en bedrijfslogo’s.',
                'description' => "Deze website brengt mijn werkervaring, eigen projecten en persoonlijke interesses samen. Mijn achtergrond in gastvrijheid, veiligheid en coördinatie krijgt een plek naast autosport en technologie.\n\nDe publieke website en het beheerportaal gebruiken dezelfde inhoud. Daardoor blijft mijn online cv gelijk aan de printversie en kan ik teksten, projecten en de uitstraling zelf aanpassen.",
                'technologies' => ['Laravel', 'Filament', 'Blade', 'Tailwind'],
                'chapters' => [
                    ['title' => 'Een persoonlijke uitstraling', 'body' => 'Donkerblauw en oranje, een eigen portret en details uit de autosport bepalen de uitstraling. Een interactieve circuitillustratie en kleine bewegingen brengen de persoonlijke kant naar voren. Bezoekers die minder beweging instellen, krijgen een rustige weergave.'],
                    ['title' => 'Zelf de inhoud beheren', 'body' => 'Het portaal biedt formulieren voor profiel, werkervaring, opleidingen, projecten, pagina’s en statistieken. Ook kleuren, afbeeldingen en gedeelde organisatielogo’s zijn aanpasbaar. Concepten kunnen eerst privé bekeken en daarna gepubliceerd worden.'],
                    ['title' => 'Eén bron voor online en print', 'body' => 'Laravel, Filament, Blade en Tailwind vormen de basis. De publieke pagina’s lezen de gepubliceerde databasegegevens; dezelfde inhoud wordt voor het printbare cv gebruikt. Afbeeldingen worden privé opgeslagen en via gecontroleerde routes geleverd.'],
                    ['title' => 'De volgende stap', 'body' => 'De website wordt lokaal ontwikkeld. Hostingcontrole en een afgeschermde testomgeving bij Vimexx volgen vóór de livegang. GitHub-statistieken zijn een latere uitbreiding; de kalender wordt nu duidelijk als demonstratie getoond.'],
                ],
                'url' => null,
            ],
            [
                'slug' => 'dutchunitedracing',
                'name' => 'DutchUnitedRacing',
                'domain' => null,
                'type' => 'Simracecompetitie',
                'visual' => 'racing',
                'number' => '03',
                'status' => 'Eigen initiatief',
                'headline' => 'Samen aan de start.',
                'subheadline' => 'Eerlijk de strijd aan.',
                'role' => 'Oprichter & Race Director',
                'started' => 'December 2023',
                'summary' => 'Mijn online simracecompetitie: gestructureerd en competitief racen, met duidelijke reglementen, wedstrijdleiding en een betrokken organisatie.',
                'description' => "Met DutchUnitedRacing zet ik een professionele online simracecompetitie op. Het doel is een gestructureerde, competitieve omgeving waarin deelnemers samen kunnen racen.\n\nAls oprichter en Race Director ben ik verantwoordelijk voor de organisatie en de dagelijkse aansturing. Van wedstrijdleiding en incidentbeoordeling tot evenementenplanning en het coördineren van het organisatieteam.",
                'technologies' => ['Assetto Corsa Competizione', 'Discord', 'Digitale wedstrijdadministratie', 'Simracingserverbeheer'],
                'chapters' => [
                    ['title' => 'Een competitie organiseren', 'body' => 'Ik verzorg de oprichting, organisatie en dagelijkse aansturing. Evenementen plannen, deelnemers informeren en het organisatieteam coördineren horen bij mijn werkzaamheden.'],
                    ['title' => 'Duidelijkheid op de baan', 'body' => 'Als Race Director houd ik me bezig met wedstrijdleiding, het ontwikkelen en handhaven van reglementen en het beoordelen van incidenten. Duidelijke afspraken vormen de basis voor een gestructureerde competitie.'],
                    ['title' => 'De omgeving achter de races', 'body' => 'We gebruiken Assetto Corsa Competizione (ACC). Discord ondersteunt communitymanagement en communicatie. Digitale wedstrijdadministratie en simracingserverbeheer maken deel uit van de organisatie.'],
                ],
                'url' => null,
            ],
        ];
    }
}
