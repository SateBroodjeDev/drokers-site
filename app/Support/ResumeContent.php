<?php

namespace App\Support;

/**
 * Selected public facts from the supplied CV, LinkedIn export and personal introduction.
 * The owner confirmed: the original CV takes precedence; LinkedIn only supplements it.
 * The source PDF and private contact details are deliberately not stored here.
 * Import source only; public pages read published CMS records.
 */
final class ResumeContent
{
    public static function profile(): array
    {
        return [
            'name' => 'Domenic Rokers',
            'headline' => 'Meldkamercentralist bij NS',
            'intro' => 'Ik houd overzicht wanneer er veel tegelijk gebeurt. Als meldkamercentralist bij NS combineer ik incidentcoördinatie met heldere communicatie en aandacht voor veiligheid. Mijn achtergrond in gastvrijheid, reizigersservice en beveiliging neem ik daarin mee.',
            'interests' => ['Autosport', 'Formule 1', 'Simracing', 'Technologie', 'Luchtvaart'],
        ];
    }

    public static function experiences(): array
    {
        return [
            [
                'role' => 'Meldkamercentralist',
                'organization' => 'Nederlandse Spoorwegen',
                'location' => 'Utrecht',
                'period' => 'Maart 2026 — heden',
                'current' => true,
                'arrangement' => null,
                'summary' => 'Coördineren en bewaken van de sociale veiligheid op stations en in treinen, in een dynamische operationele omgeving.',
                'responsibilities' => [
                    'Meldingen analyseren, risico’s inschatten en onder tijdsdruk de juiste prioriteiten stellen.',
                    'Operationele inzet coördineren en contact onderhouden met collega’s en hulpdiensten.',
                    'Meerdere systemen voor monitoring, communicatie en registratie tegelijk gebruiken en incidenten zorgvuldig vastleggen.',
                ],
            ],
            [
                'role' => 'Evenementenbeveiliger',
                'organization' => 'TSC Crowd Management',
                'location' => 'Landelijk',
                'period' => 'Augustus 2025 — heden',
                'current' => true,
                'arrangement' => 'Oproepbasis',
                'summary' => 'Veiligheid en gastvrijheid combineren bij evenementen, met aandacht voor bezoekers en een gestructureerde omgeving.',
                'responsibilities' => ['Overzicht houden bij drukte, risico’s vroeg signaleren en handelen volgens veiligheidsprocedures.'],
            ],
            [
                'role' => 'Hoofdconducteur',
                'organization' => 'Nederlandse Spoorwegen',
                'location' => 'Arnhem',
                'period' => 'Juli 2024 — maart 2026',
                'current' => false,
                'arrangement' => null,
                'summary' => 'Verantwoordelijk voor operationele en sociale veiligheid aan boord, met oog voor een veilige en aangename reis.',
                'responsibilities' => [
                    'Reizigers informeren, vervoersbewijzen controleren en vragen en klachten oplossingsgericht afhandelen.',
                    'Incidenten registreren, hulpbehoevende reizigers ondersteunen en helder communiceren met collega’s.',
                ],
            ],
            [
                'role' => 'Technical Customer Care Advisor / Wifi Specialist',
                'organization' => 'VodafoneZiggo',
                'location' => 'Nijmegen',
                'period' => 'September 2023 — juni 2024',
                'current' => false,
                'arrangement' => null,
                'summary' => 'Technische vraagstukken oplossen en klanten begeleiden bij het gebruik van diensten.',
                'responsibilities' => ['Met meerdere systemen werken en oplossingen en bevindingen zorgvuldig registreren.'],
            ],
            [
                'role' => 'Reisassistent NS',
                'organization' => 'Qarin B.V.',
                'location' => 'Station Nijmegen',
                'period' => 'Januari 2023 — januari 2024',
                'current' => false,
                'arrangement' => null,
                'summary' => 'Reizigers met een beperking ondersteunen bij hun treinreis, van instaphulp tot administratieve afhandeling.',
                'responsibilities' => ['Maatwerk en klantgerichte begeleiding bieden voor een soepele en aangename reis.'],
            ],
            [
                'role' => 'Advies- en servicemedewerker',
                'organization' => 'Coolblue',
                'location' => 'Nijmegen',
                'period' => 'Januari 2023 — september 2023',
                'current' => false,
                'arrangement' => null,
                'summary' => 'Klanten adviseren en helpen met aankopen, retouren, reparaties en datamigratie.',
                'responsibilities' => [
                    'Klanttevredenheid en de-escalatie centraal stellen bij het oplossen van klachten.',
                    'Nieuwe collega’s inwerken en bijdragen aan magazijnwerk en voorraadbeheer.',
                ],
            ],
            [
                'role' => 'Event Security Officer',
                'organization' => 'PROFI-SEC Security & Crowdmanagement',
                'location' => 'Landelijk',
                'period' => 'Maart 2022 — augustus 2025',
                'current' => false,
                'arrangement' => 'Oproepbasis',
                'summary' => 'Professioneel en klantgericht bijdragen aan veiligheid in drukke en onverwachte situaties.',
                'responsibilities' => ['Situaties beoordelen, afwegingen maken en samenwerken met andere disciplines binnen een veiligheidsplan.'],
            ],
            [
                'role' => 'Gastheer',
                'organization' => 'Efteling',
                'location' => 'Kaatsheuvel',
                'period' => 'September 2020 — januari 2023',
                'current' => false,
                'arrangement' => null,
                'summary' => 'Een gastvrije beleving verzorgen bij horeca en attracties, waaronder Python en Symbolica.',
                'responsibilities' => ['Met aandacht, oog voor detail en een speelse benadering bijdragen aan de beleving van gasten.'],
            ],
            [
                'role' => 'Horeca- en kassamedewerker',
                'organization' => 'Lagardère Travel Retail / Smullers',
                'location' => 'Nijmegen',
                'period' => 'December 2021 — mei 2022',
                'current' => false,
                'arrangement' => null,
                'summary' => 'Reizigers helpen bij Smullers Nijmegen, met aandacht voor service, snelheid en kwaliteit.',
                'responsibilities' => ['Snacks bereiden en het bestel- en betaalproces verzorgen in een drukke stationsomgeving.'],
            ],
            [
                'role' => 'Directielid',
                'organization' => 'TidalMedia',
                'location' => 'Vught',
                'period' => 'Januari 2021 — december 2021',
                'current' => false,
                'arrangement' => 'Vereniging',
                'summary' => 'Directielid bij een vereniging die podiumkunsten voor jongeren bevordert, met evenementen zoals bandjesavonden.',
                'responsibilities' => [],
            ],
            [
                'role' => 'Professionele freelancer',
                'organization' => 'YoungOnes',
                'location' => null,
                'period' => 'September 2020 — juli 2022',
                'current' => false,
                'arrangement' => 'Freelance',
                'summary' => 'Diverse opdrachten uitvoeren met flexibiliteit, creativiteit en aandacht voor de verwachtingen van opdrachtgevers.',
                'responsibilities' => [],
            ],
        ];
    }

    public static function initiatives(): array
    {
        return [
            [
                'role' => 'Oprichter & Race Director',
                'organization' => 'DutchUnitedRacing',
                'location' => null,
                'period' => 'December 2023 — heden',
                'current' => true,
                'arrangement' => 'Eigen initiatief',
                'summary' => 'Een professionele online simracecompetitie opzetten en organiseren, met een gestructureerde en competitieve omgeving voor deelnemers.',
                'responsibilities' => [
                    'De competitie organiseren, dagelijks aansturen en het organisatieteam coördineren.',
                    'Wedstrijdleiding verzorgen, reglementen ontwikkelen en handhaven en incidenten beoordelen.',
                    'Evenementen plannen en communiceren met deelnemers via onder meer Discord.',
                    'Werken met Assetto Corsa Competizione (ACC), digitale wedstrijdadministratie en simracingserverbeheer.',
                ],
            ],
        ];
    }

    public static function languages(): array
    {
        return [
            ['name' => 'Nederlands', 'code' => 'NL', 'level' => 'Moedertaal / tweetalig'],
            ['name' => 'Engels', 'code' => 'EN', 'level' => 'Professionele werkvaardigheid'],
        ];
    }

    public static function volunteering(): array
    {
        return [
            [
                'role' => 'Flag- en trackmarshal',
                'organization' => 'OCA',
                'location' => 'Circuit Zandvoort',
                'period' => 'Januari 2023 — heden',
                'current' => true,
                'arrangement' => 'Vrijwillig',
                'summary' => 'Als marshal bijdragen aan veiligheid op de baan, volgens vaste procedures en in samenwerking met andere officials.',
                'responsibilities' => ['Snel schakelen bij incidenten en veranderingen op de baan, kort communiceren en overzicht houden onder tijdsdruk.'],
            ],
        ];
    }

    public static function education(): array
    {
        return [
            [
                'title' => 'Event Security Officer',
                'issuer' => 'SVPB',
                'period' => 'Maart 2023 — juli 2024',
                'status' => 'Diploma behaald',
            ],
        ];
    }

    public static function training(): array
    {
        return [
            [
                'title' => 'Training Suïcidepreventie Volwassenen & Jongeren',
                'issuer' => null,
                'period' => null,
                'status' => 'Certificaat',
            ],
            [
                'title' => 'Omgaan met (mogelijk) suïcidale personen op en om het spoor',
                'issuer' => 'Railcenter',
                'period' => 'Februari 2025',
                'status' => 'Diploma behaald',
            ],
            [
                'title' => 'Bedrijfshulpverlener',
                'issuer' => 'NS Leercentrum',
                'period' => 'Vanaf augustus 2024',
                'status' => 'Diploma behaald',
            ],
            [
                'title' => 'Trackside Official',
                // The federation's name in the source is ambiguous; do not guess an issuer.
                'issuer' => null,
                'period' => 'Januari 2023 — december 2024',
                'status' => 'Diploma behaald',
            ],
        ];
    }

    public static function skills(): array
    {
        return [
            ['name' => 'Coördinatie & veiligheid', 'icon' => 'briefcase', 'items' => ['Incidentcoördinatie', 'Prioriteiten stellen', 'Risico-inschatting', 'Werken volgens procedures']],
            ['name' => 'Communicatie & service', 'icon' => 'spark', 'items' => ['Heldere communicatie', 'Reizigersbegeleiding', 'Klantgericht handelen', 'De-escalatie']],
            ['name' => 'Techniek & systemen', 'icon' => 'code', 'items' => ['Technische ondersteuning', 'Wifi-vraagstukken', 'Werken met meerdere systemen', 'Zorgvuldige registratie']],
        ];
    }

    public static function all(): array
    {
        return [
            'profile' => self::profile(),
            'experiences' => self::experiences(),
            'volunteering' => self::volunteering(),
            'initiatives' => self::initiatives(),
            'languages' => self::languages(),
            'education' => self::education(),
            'training' => self::training(),
            'skills' => self::skills(),
        ];
    }
}
