# Staging en releasepakketten

[Terug naar de README](../README.md) · [Hostingafspraken](hosting-en-deployment.md)

## Huidige situatie

De eigenaar heeft via SSH PHP 8.3.27, Composer 2.6.5 en GD-WebP bevestigd. De aangeleverde maplisting toont een bestaande WordPress-installatie in de documentroot van domenicrokers.nl. De portfolio-applicatie is nog niet op Vimexx geïnstalleerd. De eigenaar heeft daarna gekozen voor vervanging van WordPress op het hoofddomein na een back-up. Een aparte staginghost is optioneel; de onderstaande stagingprocedure blijft daarvoor beschikbaar.

## Gekozen route: het hoofddomein vervangen

De eigenaar heeft een eigen portfoliodatabase en gebruiker op `localhost` aangeleverd. Deze accountgegevens staan alleen in de genegeerde lokale hostinginventaris; een wachtwoord is niet opgeslagen. De eigenaar heeft op 17 oktober 2026 een geslaagde verbinding via de MySQL-client bevestigd: `SELECT VERSION()` geeft `10.6.20-MariaDB-cll-lve` en `SHOW TABLES` levert geen tabellen op. Dit controleert de clientverbinding en zichtbare tabellen; Laravel-migraties en de applicatieverbinding zijn nog niet op de host getest.

De volgende stap is een back-up van de WordPress-bestanden en de bijbehorende WordPress-database. Download ook een kopie buiten het hostingaccount. De portfoliodatabase is een afzonderlijk doel; neem niet aan dat die ook de oude WordPress-database is. Controleer vóór omschakeling dat het archief de sitebestanden bevat en dat de database-export bij de oude installatie hoort. Een gemaakt archief bewijst nog geen geslaagde hersteltest.

De eigenaar heeft deze back-upstap inmiddels als afgerond gemeld. Een hersteltest is niet bevestigd. De eerstvolgende uitvoeringsstap is het lokaal gecontroleerde ZIP met checksum uploaden naar de private home-map, de checksum op de server vergelijken en uitpakken naar een nieuwe applicatiereleasemap buiten `public_html`. Voer daar de hostingcontrole uit voordat configuratie, migraties of omschakeling volgen. Een lokale pakketcontrole bewijst geen geslaagde upload of servercontrole.

De eigenaar heeft vervolgens de upload, een overeenkomende SHA-256 en het uitpakken bevestigd. Alle controles van `scripts/hosting-check.php` slagen op de server met PHP 8.3.27, inclusief GD-WebP, runtime-mappen en aanwezigheid van vendor en frontendassets. Dit is nog geen Composer-platformcontrole of geslaagde Laravel-installatie. De volgende stap is productieconfiguratie met de eigen databasegegevens en de eerste migraties. De documentroot is nog niet omgezet; web-PHP en HTTP-gedrag zijn nog niet gecontroleerd.

De eigenaar meldt daarna dat `artisan migrate --force` is afgerond. De migratie-uitvoer is niet aangeleverd. Vervolgstappen zijn `artisan app:import-portfolio` voor de startinhoud en interactief `artisan app:create-admin` voor het eigen account. Persoonlijke uploads worden afzonderlijk overgezet; het releasepakket bevat deze niet. MFA wordt bij de eerste webaanmelding ingesteld. De publieke documentroot is nog niet omgezet.

De eigenaar heeft vervolgens ook de inhoudsimport en het aanmaken van het beheeraccount bevestigd. De huidige `public_html` is volgens de aangeleverde mapcontrole een gewone directory. De eerstvolgende stap is deze map naar een unieke bewaarlocatie buiten de documentroot verplaatsen en een symlink naar de `public/`-map van de gecontroleerde release maken. Controleer vooraf dat de bewaarlocatie niet bestaat en dat de release een `public/index.php` heeft. Mislukt het maken van de symlink, zet dan direct de oude map terug. Bij HTTP-problemen kan de exacte nieuwe symlink worden verwijderd met `unlink`, waarna de oude map wordt teruggezet; gebruik hiervoor geen recursief verwijdercommando. Dit herstelt alleen de webroot, niet eventuele databasewijzigingen.

Deze eerste koppeling wijst rechtstreeks naar de release; gedeelde configuratie/opslag en een `current`-koppeling zijn nog niet ingericht. Behoud deze release en opslag totdat een volgende releaseprocedure is getest. Na omschakeling moeten HTTPS, eventueel afzonderlijke `private_html`, web-PHP, login met MFA, private media en caches daadwerkelijk via HTTP worden gecontroleerd.

Bereid de release buiten de huidige documentroot voor, met eigen private opslag en serverconfiguratie. Gebruik voor het hoofddomein `APP_ENV=production`, `APP_DEBUG=false` en `APP_URL=https://domenicrokers.nl`, vanuit `.env.example`. Controleer platformvereisten, databaseverbinding en de geplande migraties voordat deze op de nieuwe database worden uitgevoerd. Richt daarna het eerste beheeraccount met MFA in en zet de eigen inhoud en private media over.

Na controle van de back-up en release kan tijdens een onderhoudsvenster de documentroot naar uitsluitend Laravel `public/` worden omgezet. Bewaar de oude installatie buiten de publieke documentroot voor herstel. Test daarna de publieke pagina's, beheer, media, HTTPS en cachegedrag. Exacte omschakelcommando's volgen pas na controle van de absolute paden en accountrechten; er is nog niets op de server verwijderd of geïnstalleerd.

## Optioneel: staging in DirectAdmin

Voeg `staging.domenicrokers.nl` toe via **Domeininstellingen → Voeg een domein toe**, als een zelfstandig domein binnen het hostingaccount. [Vimexx beschrijft deze inrichting](https://www.vimexx.nl/help/subdomein-als-volwaardig-domein-toevoegen). Controleer eerst of deze hostnaam al bestaat; wijzig geen bestaande installatie zonder inventarisatie.

Noteer daarna de nieuw aangemaakte domeinmap en geselecteerde web-PHP-versie. Maak een afzonderlijke MySQL-database en gebruiker voor staging. Deel geen wachtwoorden of omgevingsbestanden in chat of Git. De databaseversie, documentroot, symlinkrechten en bescherming van de testomgeving moeten nog op het account worden vastgesteld.

DNS wordt bij Cloudflare beheerd. Koppel de testhost pas na inrichting van de juiste publieke map en toegangsbeperking; controleer vervolgens een passend HTTPS-certificaat en de proxyinstellingen. Alleen `noindex` is geen toegangsbeveiliging. Gebruik bijvoorbeeld een geteste wachtwoordbeveiliging voor de volledige testhost, inclusief afbeeldingen en beheer.

## Lokaal een pakket bouwen

Voer na relevante tests uit:

```powershell
.\scripts\build-release.ps1
```

Dit bouwt de frontend, maakt een nieuwe map onder `.work/releases/` en installeert daarin uitsluitend productiedependencies uit `composer.lock`. De bestaande lokale `vendor/`, database en applicatiesleutel worden behouden. PHP en Composer uit de projectsetup en de bestaande npm-installatie zijn hiervoor nodig.

Met eigen geschikte PHP en Composer kan na `npm ci` en `npm run build` ook `php scripts/build-release.php --composer=/pad/naar/composer.phar --id=EIGEN_RELEASE_ID` worden gebruikt. Een bestaande release-ID wordt geweigerd.

Het resultaat bestaat uit een ZIP, een `.zip.sha256` en een uitgepakte releasemap. Het ZIP bevat applicatiecode, migraties, startbronnen, productiedependencies, frontend- en Filament-assets, lege runtime-mappen en een `release-manifest.json` met bestandscontrolesommen. Het manifest registreert ook hashes van het Composer-lockbestand en het frontendmanifest. Het pakket is een momentopname van de werkmap; het bewijst niet dat die inhoud al als Git-commit is vastgelegd.

Lokale `.env`-bestanden, sleutels, databasebestanden, uploads, logs, gegenereerde bootstrapcaches, ontwikkeltools en ontwikkeldependencies worden uitgesloten. De twee `.env`-voorbeelden bevatten uitsluitend sjabloonwaarden. Persoonlijke media en de database worden afzonderlijk en beveiligd overgezet; het releasepakket bevat geen actuele CMS-inhoud.

## Installatie op staging

De volgende stappen zijn een procedure, nog geen uitgevoerde hostinginstallatie. Concrete absolute paden volgen na controle van de nieuwe domeinmap.

1. Bewaar de bestaande website. Maak buiten alle publieke documentroots een eigen applicatiemap met `releases/` en `shared/` voor staging. Upload en pak het ZIP alleen daar uit. Controleer vooraf de SHA-256, bijvoorbeeld met `sha256sum -c BESTAND.zip.sha256` vanuit dezelfde map.
2. Zorg dat uitsluitend de `public/`-map van de stagingrelease door de webserver bereikbaar wordt. Test de accountrechten en koppeling voordat DNS of verkeer wordt aangesloten. Gebruik geen generiek verwijdercommando op `public_html`.
3. Maak de stagingconfiguratie vanuit `.env.staging.example` in een private gedeelde locatie. Vul HTTPS-URL en de eigen databasecredentials in. Genereer één eigen staging-`APP_KEY` met `artisan key:generate` nadat de configuratie is gekoppeld. Bewaar deze sleutel bij toekomstige updates. Staging gebruikt geen productiedatabase of productieopslag.
4. Koppel `.env` en `storage/` aan de eigen gedeelde locaties. Controleer de vereiste runtime-mappen en minimale schrijfrechten. Publiceer geen `storage/app/private`-map via een publieke link.
5. Voer vanuit de release met `/opt/alt/php83/usr/bin/php` eerst `scripts/hosting-check.php` uit. Controleer daarnaast met dezelfde PHP-runtime Composer `check-platform-reqs --no-dev`. De Composer-versie is aanwezig; compatibiliteit van dit daadwerkelijke pakket op de host is nog niet bewezen.
6. Controleer de databaseverbinding en serverversie. Voer op de nieuwe, gecontroleerde stagingdatabase `artisan migrate --force` uit en importeer startinhoud expliciet met `artisan app:import-portfolio`. Dit importeert geen lokale CMS-bewerkingen of uploads. Maak het eerste account interactief met `artisan app:create-admin` en stel MFA in.
7. Zet benodigde eigen inhoud en private media gecontroleerd over. Controleer login, herstel, concepten, publicatie, afbeeldingen, projectpagina's, cv, print en sitemap. Controleer dat privébestanden, `.env` en de projectroot nooit via HTTP bereikbaar zijn. Test daadwerkelijke afbeeldingsverwerking via het portaal.
8. Bouw eventuele configuratie- en viewcaches pas met de gekoppelde serverconfiguratie. Test web-PHP, HTTPS, cookies, toegangsbeperking en cachegedrag via de browser. Richt cron pas in voor daadwerkelijk aanwezige geplande taken; start geen permanente daemon.

`scripts/hosting-check.php` werkt ook zonder Laravel of databaseverbinding en ondersteunt `--json`. Het leest geen `.env` en schrijft geen bestanden. Het controleert de CLI-runtime, extensies, WebP-ondersteuning, aanwezigheid van assets en schrijfrechten. Een geslaagde uitslag bewijst geen werkende database, web-PHP, HTTPS, cache-instellingen of volledige deployment.

## Updates en herstel

De actuele release met GitHub-migration gebruikt [Release: persoonlijk cv, contact en GitHub](release-cv-en-github.md). Volg daarvoor de nieuwe updatehulp met afzonderlijke `prepare`, `backup`, `migrate`, `activate` en `rollback`-stappen.

De eerdere [release voor persoonlijke vormgeving en licht/donker](release-persoonlijke-vormgeving.md) beschrijft het oude pakket zonder GitHub-migration. Voor de huidige werkmap geldt de hierboven genoemde nieuwe procedure. De actuele `scripts/update-hosting.sh` biedt voorbereiding, databaseback-up, de specifiek beoordeelde aanvullende migration, activering en terugzetten; serveruitvoering is nog niet bevestigd.

De huidige GitHub-uitbreiding bevat inmiddels een nieuwe migratie. Gebruik daarvoor het nieuwe pakket met de actuele hulp en behoud de bestaande configuratie/opslag. De nieuwe tabel is aanvullend; een rollback van code verwijdert die tabel niet automatisch. Een toekomstige servercron moet afzonderlijk worden gecontroleerd.

Gebruik voor elke update een nieuwe releasemap, dezelfde stagingconfiguratie en dezelfde stagingopslag. Maak vóór migraties een herstelbare database- en mediaback-up. Controleer platformvereisten en migraties voordat een geteste `current`-koppeling wordt omgezet. Een eerdere codeversie terugzetten herstelt geen databasewijzigingen; beoordeel herstel daarom vooraf per migratie.

Een back-upherstel, serverinstallatie, MySQL-validatie, GitHub CI-run en productiedeployment zijn nog open punten. Er is een lokale pakketbouwer; er is nog geen automatische deploymentworkflow of automatische terugzetprocedure.

## Lokale verificatie op 17 oktober 2026

- Volledige testsuite: 39 tests geslaagd, 334 assertions.
- Frontendbuild en afzonderlijke Composer-productie-installatie geslaagd: 113 pakketten, zonder ontwikkeldependencies.
- Het gecontroleerde ZIP bevat 14.570 bestanden met gecontroleerde SHA-256-hashes; configuratie, lokale database en runtimegegevens zijn uitgesloten.
- Hostingcontrole op de lokale portable PHP-runtime geslaagd. Laravel 13.37.0 start vanuit de productierelease en kan de routelijst opbouwen zonder lokale configuratie over te nemen.
- De documentatielinks zijn lokaal gecontroleerd. Deze resultaten gelden niet als een GitHub CI-run of Vimexx-installatie.
