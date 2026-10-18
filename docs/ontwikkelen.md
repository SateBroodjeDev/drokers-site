# Ontwikkelen

[Terug naar de README](../README.md)

## Huidige status

De publieke website en het Filament-beheerportaal delen nu database-inhoud. Profiel, cv, organisaties en logo’s, projecten, afbeeldingen, pagina’s, vormgeving en handmatige statistieken zijn beheerbaar. Online en print gebruiken dezelfde publicatieregels. Database-sessies, databasecache, expliciete beheertoegang, verplichte TOTP en herstelcodes zijn behouden. Hosting en de GitHub-koppeling volgen nog. Zie [Je website beheren](beheerportaal.md) en [Ontwerpoplevering](ontwerpoplevering.md).

## Lokale omgeving

De ontwikkelmachine draait Windows. Node.js en Git waren aanwezig; PHP, Composer, MySQL/MariaDB, Docker en een bruikbare WSL-installatie ontbraken. Daarom gebruikt de eerste lokale basis projectgebonden PHP en Composer, met SQLite. Deze keuze maakt een snelle lokale start mogelijk. Productie blijft gericht op MySQL/MariaDB; controle van de werkelijke hostversie en een stagingtest blijven open.

| Onderdeel | Lokaal gebruikt |
| --- | --- |
| PHP | 8.4.26, portable x64 in `.tools/php/` |
| Composer | 2.10.3 in `.tools/composer.phar` |
| Node.js | 24.21.0; `.nvmrc` kiest de 24-reeks |
| npm | 11.19.0 |
| Laravel | 13.37.0 bij de eerste installatie |
| Filament / Livewire | 5.10.2 / 4.4.7 bij de eerste installatie |
| Database | SQLite; tests gebruiken een aparte database in het geheugen |

De lockbestanden zijn leidend bij latere installaties. `config.platform.php=8.3.0` in Composer voorkomt dat installeren met PHP 8.4 stilzwijgend dependencies kiest die PHP 8.3 uitsluiten. De Windows-binaries komen van PHP.net en Composer; de bootstrap controleert de gepubliceerde SHA-256-hashes. Voor HTTPS wordt de CA-bundel van curl opgehaald. De Microsoft Visual C++-runtime voor x64 moet beschikbaar zijn om deze PHP-build te starten; die is op de huidige machine aanwezig.

## Installeren op Windows

Voer vanuit de projectmap uit:

```powershell
.\scripts\setup.ps1
```

Dit script zet PHP en Composer lokaal klaar, installeert Composer-dependencies uit het lockbestand, maakt uitsluitend ontbrekende lokale configuratie en een SQLite-bestand aan, voert migrations en `app:import-portfolio` uit, publiceert Filament-assets en voert `npm ci` en de frontendbuild uit. Een bestaande `APP_KEY` blijft behouden. De lokale voorbereiding weigert een andere omgeving dan `APP_ENV=local`.

De inhoudsimport gebruikt `PortfolioSeeder` en de eerder geselecteerde gegevens in `app/Support/`. Dit is een expliciete startimport met echte, door de eigenaar aangeleverde publieke inhoud. Bestaande en verwijderde records worden niet overschreven of opnieuw gepubliceerd. `DatabaseSeeder` maakt geen inhoud of accounts aan. Eigen foto's en uploads staan buiten Git; upload het portret via het portaal als de private lokale importfoto op een nieuwe machine ontbreekt.

Een bestaande checkout bijwerken zonder dependencies opnieuw te installeren:

```powershell
.\scripts\php.ps1 artisan migrate
.\scripts\php.ps1 artisan app:import-portfolio
.\scripts\php.ps1 artisan app:import-project-details
```

De laatste opdracht vervangt alleen ongewijzigde eerdere project-startteksten door de bevestigde aanvulling van de eigenaar. Eigen redactie en publicatiestatus blijven behouden. De gewone portfolio-import vult lege organisatielogo’s aan uit de meegeleverde bronbestanden, zonder bestaande eigen logo’s te vervangen. Zie [Projectinhoud](projectinhoud.md) en [Bedrijfslogo’s](bedrijfslogos.md).

Alleen als de PowerShell-uitvoeringspolicy het script blokkeert, kun je één uitvoering expliciet starten:

```powershell
powershell.exe -NoProfile -ExecutionPolicy Bypass -File .\scripts\setup.ps1
```

De opdracht wijzigt de permanente uitvoeringspolicy niet. Voor downloads en dependency-installatie is netwerktoegang nodig. `.tools/`, `.env`, de lokale database, uploads, caches en gebouwde assets zijn uitgesloten van Git.

## Installeren met eigen PHP en Composer

Gebruik PHP 8.3 of hoger binnen de dependencyvereisten, Composer 2 en Node.js 24. Benodigde extensies zijn onder meer `intl`, `gd`, `mbstring`, `zip`, PDO en `pdo_sqlite` voor de lokale standaard. Controleer daarnaast de door Composer gemelde platformvereisten.

```text
composer setup
php artisan filament:assets
php artisan app:create-admin
php artisan serve --host=127.0.0.1
```

Deze route gebruikt dezelfde lokale voorbereiding, lockbestanden en build. De portable Windows-route is op deze machine de primaire, gecontroleerde route.

## Beheeraccount en starten

Er worden geen echte accounts geseed. Maak zelf het eerste beheeraccount aan; het wachtwoord wordt verborgen gevraagd en moet minimaal twaalf tekens bevatten:

```powershell
.\scripts\php.ps1 artisan app:create-admin
.\scripts\php.ps1 artisan serve --host=127.0.0.1
```

Open `http://127.0.0.1:8000` voor de homepage en `http://127.0.0.1:8000/admin` voor beheer. Een tweede beheeraccount wordt door het projectcommando geweigerd. Gebruik voor beheertoegang het projectcommando, niet `make:filament-user`: dat laatste kent de expliciete beheerdersvlag niet toe.

Bij de eerste aanmelding is het instellen van een authenticator-app verplicht. Sla de eenmalige herstelcodes op in je wachtwoordmanager. Profielbeheer biedt de frameworkfuncties voor wachtwoord en MFA. Openbare registratie en wachtwoordherstel per e-mail zijn niet ingeschakeld.

De server gebruikt de gebouwde frontendbestanden. Voor wijzigingen aan CSS of JavaScript kun je in een tweede terminal `npm.cmd run dev` starten; stop Vite na afloop met Ctrl+C. Alleen opnieuw bouwen kan met `npm.cmd run build`.

## Beheer herstellen via servertoegang

Probeer eerst een opgeslagen herstelcode. Als die ontbreekt, kan iemand met toegestane servertoegang het bestaande beheeraccount interactief herstellen:

```powershell
.\scripts\php.ps1 artisan app:recover-admin jouw-beheeradres@example.com
```

Met een eigen PHP-installatie gebruik je `php artisan app:recover-admin ...`. Het commando vraagt expliciete bevestiging en een nieuw wachtwoord. Het vervangt het wachtwoord, trekt het onthoudtoken en databasesessies in en wist de oude MFA-instellingen. De volgende aanmelding vereist opnieuw een authenticator en nieuwe herstelcodes. Een gedeactiveerd account blijft gedeactiveerd; het commando wijzigt de bevoegdheidsvlaggen niet.

De blijvende applicatiesleutel is nodig om bestaande MFA-instellingen te ontsleutelen. Genereer bij een gewone herstart of deployment geen nieuwe sleutel.

## Tests en build uitvoeren

```powershell
.\scripts\php.ps1 artisan test --no-ansi
.\scripts\composer.ps1 validate --strict --no-check-publish
.\scripts\composer.ps1 check-platform-reqs
npm.cmd run build
```

De suite controleert onder meer TOTP, herstelcodes, accountbeheer, profiel- en stijlwijzigingen, publiceren en intrekken van inhoud, gedeelde logo’s, uploads, private previews, sitemap, galerijen en projectredirects. Tests gebruiken een geïsoleerde database en tijdelijke opslag; ze wijzigen de lokale websitegegevens niet. De CMS-formulieren worden ook via Livewire aangeroepen.

## Een hostingpakket bouwen

Met `.\scripts\build-release.ps1` bouw je assets en een afzonderlijke productie-installatie onder `.work/releases/`. Je lokale dependencies en gegevens blijven behouden. Het resultaat is een ZIP met controlesom en bestandsmanifest; configuratie en uploads worden niet meegenomen. Zie [Staging en releasepakketten](staging-en-releases.md) voor de voorbereiding en de nog uit te voeren hostingstappen. `ReleasePackageTest` controleert uitsluiting van vertrouwelijke runtimegegevens, onvolledige assets en bescherming tegen overschrijven.

## Repository en GitHub

`main` bevat de gecontroleerde hoofdversie. Werk met korte branches, bijvoorbeeld `feat/projectbeheer`, `fix/cv-datums` en `docs/hosting`. Een wijziging beschrijft het zichtbare resultaat en bevat relevante verificatie. Leg veranderingen aan gedrag of technische uitgangspunten in dezelfde wijziging vast in de documentatie.

Voorgestelde GitHub-inrichting bij de implementatie:

- Issues voor de afgebakende onderdelen uit de roadmap.
- Pull requests met een korte beschrijving en relevante checks.
- Bescherming van `main` tegen onbedoelde overschrijving; passende checks voordat wordt samengevoegd.
- Dependabot of een gelijkwaardige updatecontrole voor Composer en npm.
- Een afzonderlijke staging- en productieomgeving voor deploymentsecrets.
- Een handmatig te starten releaseworkflow; geen productiedeployment op iedere codepush.

De repository bevat nu `.github/workflows/checks.yml` voor PHP 8.3/8.4 en SQLite/MySQL 8.4, plus `.github/dependabot.yml`. Die workflow wordt pas na een push op GitHub uitgevoerd; een lokale test is geen bewijs dat de CI-run geslaagd is. Er is geen deploymentworkflow en er zijn nog geen externe branchregels, issues of hostingsecrets ingesteld.

## Afspraken voor implementatie

Gebruik gangbare Laravel- en Filament-conventies. Publieke weergave en beheer gebruiken hetzelfde datamodel en dezelfde publicatieregels. Alle afgesproken redactionele inhoud is via het portaal te beheren; wachtwoorden en API-tokens worden als configuratie beheerd.

Databasewijzigingen krijgen migrations. Fictieve testgegevens worden gescheiden gehouden van de expliciete, geselecteerde portfolio-startinhoud. Kopieer geen beheeraccounts, privébronbestanden of toekomstige productiedatabases naar seeders of fixtures. Nieuwe dependencies krijgen een concreet doel en worden op onderhoud en compatibiliteit beoordeeld.

Voeg na initialisatie de framework-specifieke `.gitignore`-regels samen met de bestaande regels. Uploads, logs, credentials, lokale databases en back-ups blijven ongeversioneerd. Lockbestanden horen wel in Git.

## Kwaliteitschecks

De eerste CI-workflow installeert dependencies uit lockbestanden, gebruikt de gekozen PHP-versie en een representatieve database en voert toepasselijke checks uit:

| Check | Wat die moet aantonen |
| --- | --- |
| PHP-tests | Publicatie, autorisatie, validatie en berekeningen werken volgens ontwerp |
| Frontendbuild | Publieke assets zijn daadwerkelijk te bouwen met de vastgelegde dependencies |
| Codecontrole | Projectcode volgt de afgesproken stijl en relevante frameworkregels |
| Dependencycontrole | Bekende problemen in gebruikte packages zijn zichtbaar en worden beoordeeld |
| Stagingcontrole | De gebouwde release functioneert op de gekozen hostingomgeving |

De huidige test- en buildcommando's staan hierboven. `CmsContentTest` bevat de inhoudelijke publicatie-, formulier- en mediacontroles. Een geslaagde build bewijst geen goede beheertoegang of juiste dataselectie; die worden afzonderlijk getest. CI en MySQL op de host zijn nog niet lokaal bewezen.

## Belangrijke automatische tests

1. Publicatie: concepten, archieven en verwijderde items zijn niet publiek, ook niet via detailroute, sitemap, tags, tellers of media.
2. Toegang: een bezoeker of onbevoegd account kan geen beheeractie uitvoeren; 2FA en herstelcodes volgen de afgesproken flow.
3. Validatie: dubbele slugs, ongeldige kleurcodes, ontbrekende statistiekbronnen en verboden uploads worden geweigerd. Cv-periodes zijn momenteel redactionele tekst, geen berekende datumbereiken.
4. Samenhang: publiceren en intrekken wijzigen de juiste pagina's en tellingen, inclusief cache-invalidatie.
5. Media: conceptbestanden en originele uploads zijn niet openbaar, terwijl een veilige gepubliceerde variant wel werkt.
6. Redirects: een gewijzigd projectpad verwijst naar het huidige publieke project zonder lus of toegang tot verborgen inhoud.
7. GitHub-fase: paginering, meetdefinities, rate limits, time-outs en behoud van de laatste geldige meting.

Tests gebruiken geïsoleerde data en tijdelijke opslag. Externe HTTP-antwoorden worden gesimuleerd. Reële API-tokens zijn niet nodig om de tests te draaien.

## Handmatig controleren

Controleer bij wijzigingen aan de publieke ervaring mobiel en desktop, toetsenbordbediening, focus, leesbaarheid, ontbrekende optionele inhoud en een lange projecttitel. Controleer ook het printbare cv. Gebruik naast automatische controles een echte beheerhandeling: aanmaken, previewen, publiceren en weer intrekken.

De eerste release krijgt daarnaast een check van canonieke URL's, metadata en de sitemap, toegangsbeveiliging op staging, deployment en herstel van een back-up. De acceptatiecriteria F01–F15 uit het functioneel ontwerp vormen de inhoudelijke controlelijst.

## Wanneer een onderdeel af is

De afgesproken functie werkt, relevante tests en build slagen, er worden geen privégegevens gepubliceerd en de documentatie beschrijft het werkelijke gedrag. Een functie waarvoor hostinstellingen nodig zijn, is pas volledig gevalideerd nadat die op staging is gecontroleerd. Een nog niet uitgevoerde check wordt als open punt benoemd.
