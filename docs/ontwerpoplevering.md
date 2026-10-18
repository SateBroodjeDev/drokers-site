# Ontwerp en beheerportaal

[Terug naar de README](../README.md)

## Wat er nu staat

De website is op verzoek van Domenic opgezet als een uitgebreid persoonlijk cv, aangevuld met projecten, interesses en statistieken. De aparte sectie ‘Voor werkgevers’ en de bijbehorende portaalvelden zijn verwijderd. Contact behoudt gewone routes naar achtergrond en cv. Het bevestigde GitHub-profiel SateBroodjeDev is lokaal ingesteld. Een eerste repository-/sterrenkoppeling, meetdatum, synchronisatieknop en zesuurlijke planning zijn gebouwd; dit is geen commit- of bijdragenkalender. Hiervoor is een nieuwe databasemigratie toegevoegd. Deze uitbreiding staat nog niet op Vimexx.

De contactpagina is vervolgens heringericht: beheerde e-mail-/profiellinks links, een compacte persoonlijke kaart met cv-routes rechts en een kleine deelactie. Contactkop, introductie en linktoelichtingen zijn via het portaal te wijzigen. De lege toestand bevat geen fictief e-mailadres of onbevestigde reactietermijn. Deze lokale wijziging is nog niet op Vimexx geplaatst en zit niet in releasepakket `20261017T213959Z`.

De introductie zonder portret gebruikt nu een rustige profielkaart: naam, functie, beheerde eigenschappen en een link naar de achtergrond. Het grote aanhalingsteken, roze vlak en de humoristische tekst zijn uit deze terugvalweergave gehaald. De persoonlijke noot blijft opgeslagen in het portaal en kan bij een eigen portret worden gebruikt. Deze aanpassing zit nog niet in het eerder gemaakte releasepakket.

De header gebruikt inmiddels een eigen DR-beeldmerk met een oranje bewegingslijn in plaats van initialen in een rondje. De favicon gebruikt hetzelfde getekende beeldmerk. Een via het portaal gekozen eigen logo houdt voorrang. Deze aanvulling is lokaal gebouwd na het eerder klaargezette releasepakket `20261017T213959Z`; dat bestaande ZIP bevat deze logo-aanpassing nog niet.

Het portfolio gebruikt de **Paddock**-kleuren in een donkere persoonlijke richting, geïnspireerd op de door Domenic aangeleverde Lovable-beelden. De naam krijgt een kleurverloop, de foto een groot afgerond kader en de interesses rustige kaarten. De voorbeeldpersoon en placeholders zijn niet gebruikt. Het cv is compacter met ervaring links en opleidingen en vaardigheden rechts; projecten krijgen rustige kaarten en een inklapbaar techniekfilter. Nacht en Licht zijn instelbaar in het portaal. Behulpzaam, verantwoordelijk en ondernemend blijven de persoonlijke uitgangspunten.

| Pagina | Uitwerking |
| --- | --- |
| Home | Donkere persoonlijke introductie, foto of tekstkaart, eigenschappen, interessekaarten, drie gepubliceerde werkitems en eigen initiatieven; cv en contact als acties |
| Over mij | Beheerde biografie, portret, route met eigen loopbaanhaltes en interesses |
| Cv | Compacte naamkop met printactie, twee kolommen voor ervaring en ontwikkeling, gedeelde logo’s, uitklapbare werkzaamheden, vrijwilligerswerk, initiatieven en talen |
| Printbaar cv | Dezelfde gepubliceerde inhoud en logo’s; afdrukken of opslaan als PDF via de browser |
| Projecten | Beheerde kaarten, zoeken, techniekfilters, lege resultaten, detailverhalen, covers en galerijen |
| Development | Actuele projectteller, handmatige cijfers met bron en peildatum, gemarkeerde kalenderdemonstratie en uitleg over de sitebasis |
| Contact | Bewust ingevulde openbare links; geen automatisch overgenomen privécontactgegevens |
| Privacy | Beheerde tekst voor de lokale ontwerpversie; definitieve hostingtekst vóór livegang controleren |

Het beheerportaal gebruikt oranje als accent en heeft een startscherm met directe routes naar vijf beheerlijsten. Beveiligde toegang en verplichte MFA zijn behouden.

## Zelf aanpassen

Profiel, teksten, foto’s en vormgeving komen uit de database. Onder **Profiel & vormgeving** zijn drie schema’s beschikbaar: Paddock, Graphite en Electric. Eigen accent-, achtergrond- en tekstkleuren kunnen het schema aanvullen. De eigenaar kan de kopstijl kiezen, beweging uitschakelen, contactlinks toevoegen en secties verbergen.

**Organisaties** beheert werkgevers, opleiders en verenigingen met één gedeeld logo. Zeven logo’s uit officiële bronnen zijn toegevoegd en via de bibliotheek vervangbaar. Witte logo’s krijgen een donkere drager, ook in de printversie. Bestanden worden lokaal geladen; ontbrekende logo’s gebruiken de organisatienaam. Zie [Bedrijfslogo’s en beeldbronnen](bedrijfslogos.md).

Cv, projecten, pagina’s, persoonlijke verhalen en statistieken zijn afzonderlijk te publiceren of in te trekken. Nieuwe inhoud begint als concept. De afgeschermde preview toont één geselecteerd concept in de context van de gepubliceerde site. Een gepubliceerde wijziging gaat via **Opslaan en publiceren** direct naar de publieke weergave. Er is geen afzonderlijke conceptrevisie naast een gepubliceerde versie.

De vaste componenten, illustraties en pagina-indeling blijven code. De concrete stappen staan in [Je website beheren](beheerportaal.md); het huidige model staat in [Datamodel](datamodel.md).

## Echte inhoud en bronkeuze

De startinhoud is gebaseerd op Domenics toelichting en aangeleverde documenten. Het eerdere cv is leidend; `Profile.pdf` vult dit uitsluitend aan. Gastvrijheid, veiligheid en coördinatie geven de professionele kant inhoud. Autosport, technologie, initiatief en humor bepalen de persoonlijke details. De marshalrol staat apart als vrijwilligerswerk. Zie [Cv-verwerking](cv-verwerking.md).

GlobeTrotr, deze website en DutchUnitedRacing zijn als projecten geïmporteerd. De eigenaar heeft doel, bijdrage en technieken van GlobeTrotr en DutchUnitedRacing aangevuld. GlobeTrotr is uitgewerkt als reisplatform; DutchUnitedRacing als simracecompetitie met de rol Oprichter & Race Director. Die laatste rol is ook in beide cv-weergaven verwerkt. Projectaantal, zoekfunctie en filters volgen de gepubliceerde database-inhoud. Zie [Projectinhoud](projectinhoud.md).

Het portfolio heeft echte screenshots als cover en galerij. De andere projecten gebruiken eigen ontwerpillustraties totdat echte beelden worden toegevoegd. De detailpagina’s hebben een inhoudsopgave, hoofdstukken en een galerij met bijschriften. Afbeeldingen kunnen groot geopend worden; de viewer werkt met toetsenbord, Escape, terugkerende focus en een gewone afbeeldingslink zonder JavaScript. Het projectformulier heeft vier overzichtelijke tabbladen en een sorteerbare beeldlijst.

De kalender is een zichtbare demonstratie, zonder gemeten GitHub-activiteit. Handmatige cijfers vereisen waarde, bron en peildatum. Er zijn geen fictieve cijfers vooringevuld. De site blijft lokaal `noindex, nofollow`.

## Ontwerpsysteem

| Onderdeel | Standaardwaarde |
| --- | --- |
| Achtergrond | `#F2F3F6` |
| Tekst | `#172438` |
| Donker vlak | `#14243B` |
| Accent | `#F08045` |
| Koppen en lopende tekst | DM Sans Variable, lokaal gebundeld |
| Optioneel redactioneel accent | DM Serif Display Italic, lokaal gebundeld |
| Technische labels | Systeem-monospace |

De vaste tokens en responsive regels staan in `resources/css/app.css`. `PortfolioContent` levert gevalideerde kleurwaarden aan `site-layout`. De gedeelde onderdelen staan onder `resources/views/components/`. De eigen iconen heten `site-icon`, zodat ze niet botsen met Blade UI Icons uit Filament.

De lettertypen worden via npm beheerd en door Vite als lokale bestanden gebouwd. Licenties: [DM Sans OFL](../public/fonts/licenses/dm-sans-OFL.txt) en [DM Serif Display OFL](../public/fonts/licenses/dm-serif-display-OFL.txt).

## Interactie en mobiel

- Het mobiele menu sluit met Escape en na navigeren; zonder JavaScript blijft navigatie beschikbaar.
- Zoeken en filteren gebruiken deelbare GET-URL’s. Kopiëren biedt een terugval als het klembord niet beschikbaar is.
- Het portretkader reageert beperkt op een fijne muisaanwijzer; bij touch en minder beweging blijft het stil.
- Circuit, Code en Karakter hebben toetsenbordbediening en tonen alleen gepubliceerde verhalen.
- De circuitauto rijdt uitsluitend na een klik één rondje. Pauze, hervatten, een ander verhaal, een verborgen tabblad en beweging buiten beeld worden afgehandeld.
- `prefers-reduced-motion` en de profielschakelaar zetten de beweging uit. Een gewijzigde bezoekersvoorkeur wordt tijdens het bezoek gevolgd.
- Zonder JavaScript blijven gepubliceerde verhalen leesbaar en zijn inactieve interactieknoppen verborgen.
- Er is geen geluid of automatisch herhalende animatie. De footerklok gebruikt `Europe/Amsterdam`.

De circuitillustratie is eigen inline SVG, geen kaart van Zandvoort en geen gemeten raceprestatie. Interactie en beeldviewer gebruiken gewone JavaScript zonder extra bibliotheken. De gebouwde applicatie-JavaScript is ongeveer 7,4 kB vóór compressie.

## Afbeeldingen en eerdere ontwerpronde

Het huidige hoofdbeeld bestaat uit code, SVG en het echte portret uit het cv. De foto is zonder AI-bewerking naar WebP omgezet. De privébron is 300 × 300 pixels; de huidige machine heeft hem in de mediabibliotheek geïmporteerd. De publieke site gebruikt de gecontroleerde mediaroute. De oorspronkelijke PDF’s en persoonlijke broncontactgegevens worden niet aangeboden.

De eerdere abstracte groene sculptuur is in de eerste ontwerpronde met **imagegen** gemaakt en wordt niet meer in het actieve ontwerp gebruikt. Het historische bestand is [portfolio-sculpture.webp](../public/images/portfolio-sculpture.webp), 1536 × 1024 pixels. De herkomst blijft hieronder vastgelegd.

Gebruikte oorspronkelijke prompt:

> Create a premium editorial 3D artwork for a personal developer portfolio website. Landscape composition, ideally 1536x1024. A single beautifully crafted sculptural continuous folded ribbon, forming a softly angular interlocking loop, like an abstract lowercase cursive d without any actual letters or typography. Deep forest green satin ceramic exterior, a few vibrant pale chartreuse inner surfaces. The object stands on a warm ivory seamless studio surface and background #efeee6. Architectural, tactile, precise soft bevels, fine ceramic texture, natural daylight from upper left, gorgeous soft contact shadows, subtle analog grain. Camera at an elevated three-quarter angle, object filling about 75% of frame. Art direction: refined independent design studio, sophisticated and warm, no glass spheres, no chrome, no neon, no gradients in the background, no purple, no floating objects, no text, no logos, no people, no devices, no watermark. A real sculptural hero image with strong silhouette and ample breathing space around the object.

## Lokale verificatie

Na de werkgevers- en GitHub-uitbreiding slagen **49 PHP-tests met 435 assertions**, de frontendbuild en 11 contactbrowsercontroles. Ze controleren ook paginering, uitsluiten van forks/archieven/privégegevens, deduplicatie, terugval bij API-fouten, rate limits, dubbele synchronisaties en handmatig verversen via het portaal. Een echte openbare synchronisatie van het bevestigde profiel `SateBroodjeDev` is daarna geslaagd. Die meting leverde één openbare eigen repository en nul ontvangen sterren op; dit is een momentopname, geen kwaliteitsscore. Hostingmigratie en cron zijn nog niet uitgevoerd.

Na de echte meting slagen 12 aanvullende browsercontroles voor home, contact en Development op 320, 390, 768 en 1440 pixels, inclusief profielverwijzing, opgeslagen cijfers en de werkgeverssectie. De nieuwe screenshots hebben prefix `employer-github-` onder `.work/design-previews/`.

De bezoekersschakelaar voor licht/donker is daarna toegevoegd en gecontroleerd met **48 geslaagde browserchecks**: omschakelen, onthouden bij herladen en navigatie, synchroniseren tussen tabbladen, vier breedtes in beide weergaven, het mobiele menu, ontbrekende browseropslag, ongeldige opgeslagen voorkeuren, gebruik zonder JavaScript en witte printachtergronden. De volledige PHP-suite slaagt met 41 tests; de vervolgens aangevulde CMS-suite slaagt met 13 tests en 143 assertions, inclusief paletten en behoud van eigen kleuren. De frontendbuild en documentatielinks slagen. De knop en de bijbehorende nieuwe beheerinstellingen zijn nog niet op Vimexx geïnstalleerd.

De daaropvolgende donkere uitwerking heeft **41 geslaagde PHP-tests met 364 assertions** en een geslaagde frontendbuild. Een eerste browserronde met 55 controles dekt zeven pagina's op zes breedtes, het mobiele menu, interesseverwijzingen en wissels, het techniekfilter, gebruik zonder JavaScript en lichte afdrukkleuren. De visuele controle leidde tot extra correcties aan opleidingsvlakken en projectkaarten. Screenshots van de donkere versie hebben prefix `night-` in `.work/design-previews/`. De nieuwe variant is nog niet op Vimexx geplaatst.

Na deze correcties zijn nog 11 controles geslaagd voor home, cv, projecten en Over mij op desktop en mobiel, donkere opleidingsvlakken en witte afdrukkleuren. De screenshots zijn opnieuw gemaakt; `night-home-desktop-top.png`, `night-home-mobile-top.png`, `night-cv-desktop.png` en `night-projects-desktop.png` tonen de laatste versie. `night-cv-print.pdf` bevat de gecontroleerde printuitvoer. Er zijn geen dependencies of databasemigraties toegevoegd; bestaande CMS-inhoud blijft behouden.

De persoonlijke herziening is lokaal gecontroleerd met **40 PHP-tests en 348 assertions** en een geslaagde frontendbuild. De extra controles bewaken de publicatieregels van werkervaring op home, logo's van verborgen organisaties, zichtbaarheid van de cv-pagina en aanpasbare homepagekoppen via het portaal. De eerste hostingversie werkt volgens de eigenaar; deze nieuwe vormgeving is nog niet op Vimexx geplaatst. Publieke pagina's tonen geen algemene ontwerpversiebanner meer; een afgeschermde beheervoorvertoning houdt wel zijn duidelijke label. `noindex, nofollow` blijft behouden totdat de definitieve publicatiecontrole is afgerond.

Ook zijn **37 browsercontroles** geslaagd voor vijf pagina's op 320, 390, 600, 768, 1024 en 1440 pixels, het mobiele menu, de drie persoonlijke interessekeuzes en navigatie zonder JavaScript. Afbeeldingen zijn expliciet geladen voordat ontbrekende bestanden werden gecontroleerd. Er zijn geen horizontale overflow, ontbrekende afbeeldingen of browserfouten gevonden. De nieuwe screenshots staan lokaal onder `.work/design-previews/personal-refresh-desktop-top.png`, `personal-refresh-desktop.png`, `personal-refresh-mobile-top.png` en `personal-refresh-mobile.png`.

De vernieuwde publieke pagina’s zijn met Edge op desktop en mobiel gecontroleerd: veertien paginacontroles zonder ontbrekende beelden of JavaScript-fouten. Daarnaast passen de belangrijkste routes op 320, 360, 768 en 1024 pixels zonder horizontale overflow; navigatie zonder JavaScript is meegenomen.

De persoonlijke interacties zijn opnieuw gecontroleerd met 51 browserchecks voor muisdiepte, toetsenbord, alle verhaalkeuzes, rondje/pauze/hervatten, finish, gewijzigde bewegingsvoorkeur en gebruik zonder JavaScript. De portaalcontrole heeft 22 checks voor de zes hoofdschermen, opslaan en terugzien van een profielwijziging op home en print, mobiel en browserfouten. De portaalcontrole gebruikt een afzonderlijke tijdelijke database, zonder de echte beheeraccounts of website-inhoud te wijzigen.

Na de project- en logo-uitbreiding zijn **35 PHP-tests met 286 assertions** geslaagd. Ze controleren onder andere CMS-formulieren, publicatie, intrekken, afbeeldingen, gedeelde logo’s, previewtoegang, redirects, sitemap, herhaalbare import en de bestaande MFA- en herstelstromen. De frontendbuild is opnieuw uitgevoerd. De eerdere Composer-validatie en lokale platformcontrole blijven van toepassing; dependencies zijn niet gewijzigd. Dit is geen WCAG-certificering, geslaagde GitHub Actions-run of hostingvalidatie.

Daarnaast zijn 29 browserchecks voor de beeldviewer, galerij, hoofdstukformulier en het opslaan via het projectportaal geslaagd. Galerijvolgorde en bronvermelding zijn automatisch getest. De veertien paginacontroles en 32 breedtecontroles zijn na deze uitbreiding opnieuw geslaagd. Lazy-loaded afbeeldingen zijn voor de beeldcontrole expliciet geladen.

Screenshots en voorbeeld-PDF staan in de genegeerde map `.work/design-previews/`. De actuele previews zijn onder andere `home-desktop-top.png`, `project-story-1440.png`, `project-gallery-1440.png`, `project-gallery-390.png` en `project-editor-images.png`.

```powershell
.\scripts\php.ps1 artisan serve --host=127.0.0.1
```

Open lokaal `http://127.0.0.1:8000`; beheer staat op `/admin`. De browsercontroleservers worden na gebruik gestopt.

## Volgende oplevering

De persoonlijke ontwerpvernieuwing is lokaal gebouwd; de eerste eerdere versie werkt volgens de eigenaar op Vimexx. Controleer het nieuwe ontwerp voordat een nieuw releasepakket wordt uitgepakt en gekoppeld. Bestaande serverconfiguratie, applicatiesleutel, CMS-inhoud en private uploads moeten bij een update behouden blijven. Aanvullende projectbeelden en openbare contactlinks kunnen via het portaal worden aangevuld. Volledige hostingacceptatie, controle van actuele functies, definitieve metadata, indexeerbaarheid en back-upherstel blijven open. Automatische GitHub-statistieken volgen afzonderlijk.
