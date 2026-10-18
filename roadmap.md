# Roadmap

[Terug naar de README](README.md)

De fasen hebben concrete eindvoorwaarden. Er zijn nog geen doorlooptijden of leverdatums afgesproken. De lokale website en het inhoudsbeheer zijn gebouwd en gecontroleerd. De eigenaar heeft de eerste versie op Vimexx geïnstalleerd en meldt dat deze werkt. De persoonlijke ontwerpvernieuwing staat lokaal; uitrol van deze update, volledige hostingacceptatie en back-upherstel zijn nog open. Een aparte staginghost is optioneel bij de gekozen vervanging van WordPress.

## Fase 0 — Ontwerp en documentatie

- [x] Productdoel, doelgroepen en scope uitwerken.
- [x] Pagina's, beheeronderdelen en publicatiegedrag beschrijven.
- [x] Architectuur en logisch datamodel uitwerken.
- [x] Visuele richting en benodigde inhoud beschrijven.
- [x] Statistiekdefinities en aanpak voor GitHub vastleggen.
- [x] Hosting, deployment, herstel en ontwikkelwerkwijze beschrijven.
- [x] Besluiten en nog te controleren gegevens vastleggen.

**Gereed wanneer:** de README naar de samenhangende documenten verwijst en plannen duidelijk van bestaande functionaliteit zijn onderscheiden. Er wordt in deze fase nog geen applicatie uitgerold.

## Fase 1 — Hostingcontrole en applicatiebasis

- [x] Lokale PHP-, Composer-, Node- en databaseomgeving kiezen en vastleggen (SQLite voor de lokale start).
- [ ] Accountvoorwaarden voor PHP, database, SSH, opslag en cron controleren.
- [x] Laravel-skelet toevoegen met behoud van documentatie en Git-geschiedenis.
- [x] Compatibele Filament- en frontenddependencies vastleggen in lockbestanden.
- [x] `.env.example`, projectconfiguratie en lokale installatie-instructies toevoegen.
- [x] Lokale releasepakketbouwer met geïsoleerde productiedependencies, checksums en hostingcontrole toevoegen.
- [ ] Stagingomgeving en eerste hostingrelease inrichten en testen.
- [x] CI met een frontendbuild en een gerichte basiscontrole toevoegen (workflow voorbereid; GitHub-run nog niet uitgevoerd).

**Gereed wanneer:** een verse checkout lokaal start, de basischecks slagen en een minimale applicatie via de juiste `public/`-map op afgeschermde staging draait.

## Fase 2 — Gegevens en beheerportaal

- [x] Migrations en relaties voor de eerste release toevoegen.
- [x] Basis voor beheeraccount, expliciete beheertoegang, aanmeldbeperking en verplichte 2FA inrichten.
- [x] Herstelcodes en serverherstel controleren en documenteren.
- [x] Profiel, contactlinks, cv-onderdelen en vaardigheden beheerbaar maken.
- [x] Projecten, projectstatus, volgorde en uitlichten toevoegen.
- [x] Concepten, preview, publiceren, intrekken en archiveren uitwerken.
- [x] Private uploads, veilige afbeeldingsvarianten en mediagebruik toevoegen.
- [x] Handmatige statistieken, projectteller en pagina-instellingen toevoegen.
- [x] Gerichte tests voor publicatie, toegang, validatie en mediabescherming uitvoeren.
- [x] Organisaties met herbruikbare bedrijfslogo’s en beheerbare kleurenschema’s toevoegen.

**Gereed wanneer:** de eigenaar een compleet cv-item en project met afbeelding kan publiceren en intrekken; een uitgelogde bezoeker ziet geen conceptinhoud. De relevante delen van F02–F09 en F13–F14 zijn gecontroleerd.

## Fase 3 — Publieke website en inhoud

- [x] Eerste visuele basis en herbruikbare componenten uitwerken.
- [x] Homepage, over mij, cv en printweergave als ontwerpversie bouwen.
- [x] Projectoverzicht, URL-filters en detailpagina's als ontwerpversie bouwen.
- [x] Developmentpagina met herkenbare kalenderpreview en contactpagina vormgeven.
- [x] Aangeleverde persoonlijkheid, marshalrol en interesses verwerken in tekst, interactieve circuitvisual en bewegende details.
- [x] Ontwerppagina's verbinden met beheerde, gepubliceerde gegevens en redirects toevoegen.
- [x] Aangeleverde cv-inhoud, portretfoto en persoonlijke teksten verwerken in het publieke prototype en de printweergave.
- [x] Geselecteerde startinhoud importeren zonder latere redactie te overschrijven; online en print gebruiken het CMS.
- [x] Bevestigde projectbeschrijvingen en technieken verwerken, zeven organisatielogo’s toevoegen en projectgalerij met bijschriften en grote beeldweergave bouwen.
- [ ] Aanvullende projectdetails, eigen bedrijfslogo’s en contactlinks invoeren en vóór livegang controleren.
- [ ] Metadata, canonieke URL's, sitemap en passende privacyuitleg toevoegen.
- [x] Eerste ontwerp op desktop en mobiel, menu, filters, lege toestanden en print controleren.
- [ ] Volledige inhoud, toegankelijkheid en publicatiegedrag vóór livegang controleren.

**Gereed wanneer:** F01–F15 op staging voldoen, alle persoonlijke feiten gecontroleerd zijn en de site met werkelijke inhoud op mobiel en desktop bruikbaar is.

## Fase 4 — Eerste livegang

- [ ] DNS-, certificaat-, proxy- en cache-instellingen controleren.
- [ ] Productiecredentials, documentroot, maprechten en logging inrichten.
- [ ] Beheer en conceptmedia op de productieconfiguratie controleren.
- [ ] Back-up van database en media maken en herstel op een afgeschermde omgeving oefenen.
- [ ] Release- en terugzetprocedure vanaf een gekozen commit oefenen.
- [ ] Productierelease bewust starten en de primaire routes controleren.
- [ ] Testinhoud uitsluiten en installatie-/beheerhandleiding bijwerken naar het werkelijke gedrag.

**Gereed wanneer:** `https://domenicrokers.nl` de gecontroleerde inhoud toont, beheer werkt, de gekozen commit traceerbaar is en herstel is getest. Een automatische GitHub-koppeling is hiervoor geen voorwaarde.

## Fase 5 — Automatische GitHub-statistieken

- [x] Profiel SateBroodjeDev bevestigd; meetdefinities voor openbare eigen repositories en sterren vastgelegd, zonder verplicht token.
- [ ] Integraties, snapshots en synchronisatielogs aan het model toevoegen.
- [x] Serverzijdige synchronisatie voor repositories en sterren met paginering, time-outs, locks en rate-limitafhandeling bouwen.
- [ ] Cron en laatste-succescontrole op Vimexx testen.
- [ ] Publieke statistieken en optionele kalender met bron en periode tonen.
- [x] API-fouten, ontbrekende tokens en de laatst geldige repositorymeting met gesimuleerde HTTP-antwoorden testen.
- [ ] Aan/uit, handmatig verversen en status in het beheerportaal toevoegen.

**Gereed wanneer:** de acceptatiecriteria uit [Statistieken](docs/statistieken.md) voldoen en een werkelijke synchronisatie op de hosting is gecontroleerd zonder secrets te publiceren.

## Fase 6 — Uitbreiden op basis van gebruik

Mogelijke vervolgstappen: Engelse vertaling, blog/projectupdates, aanvullende statistiekbronnen en een afzonderlijke cv-PDF. Licht/donker is gebouwd. Een compact cv en contactformulier met privé-inbox, spambeperking en beheerafhandeling zijn lokaal toegevoegd na de eerste release; de contact-migration en scheduler moeten nog op hosting worden uitgevoerd en gecontroleerd. Ieder nieuw onderdeel krijgt eerst een concrete behoefte, scope en acceptatiecriteria.

## Eerstvolgende stap

Projectverhalen, bedrijfslogo’s en eerste projectbeelden zijn lokaal verwerkt. Controleer resterende contactlinks en publicatiekeuzes via het [beheerportaal](docs/beheerportaal.md). PHP CLI, Composer en de WebP-functie zijn door de eigenaar op Vimexx bevestigd. De eigenaar kiest vervanging van WordPress op het hoofddomein na een back-up en heeft een portfoliodatabase aangeleverd. Controleer nu de databaseverbinding, versie en bestaande tabellen volgens [Staging en releasepakketten](docs/staging-en-releases.md). De lokale pakketbouwer is gereed; serverinstallatie, web-PHP, toegangsbeveiliging en back-upherstel blijven open. De GitHub-koppeling volgt als afzonderlijke uitbreiding.
