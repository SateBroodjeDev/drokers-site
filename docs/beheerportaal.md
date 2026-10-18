# Je website beheren

[Terug naar de README](../README.md)

De website leest profiel, cv, projecten, pagina-inhoud en handmatige statistieken uit de database. Je hoeft hiervoor geen Blade- of PHP-bestanden te wijzigen. De vaste pagina-indeling en de circuitillustratie blijven onderdeel van de applicatiecode.

## Openen en aanmelden

Start lokaal de website en open `http://127.0.0.1:8000/admin`. Maak alleen als er nog geen beheeraccount is een account met:

```powershell
.\scripts\php.ps1 artisan app:create-admin
```

Het commando vraagt je gegevens en een verborgen wachtwoord. Bij de eerste aanmelding koppel je een authenticator-app en bewaar je de herstelcodes. Er is geen standaardwachtwoord. Account- en MFA-herstel staan in [Ontwikkelen](ontwikkelen.md).

| Onderdeel in het menu | Wat je aanpast |
| --- | --- |
| Profiel & vormgeving | Naam, functie, introductie, biografie, portret, homepage, kleuren, letterstijl, beweging, contactlinks en footer |
| Cv, pagina’s & cijfers | Werkervaring, vrijwilligerswerk, eigen initiatief, opleidingen, trainingen, vaardigheden, talen, pagina’s, persoonlijke verhalen en statistieken |
| Organisaties | Werkgevers, opleiders en verenigingen, met een gedeeld logo en optionele website |
| Projecten | Projectkaarten, detailpagina’s, status, technieken, links, cover en galerij |
| Afbeeldingen | Uploads, herkenbare bestandsnamen in de bibliotheek en alternatieve tekst |

## Opslaan en publiceren

Nieuwe inhoud begint als **concept**. Vul de gegevens in, sla op en kies **Voorbeeld bekijken**. De preview is alleen voor een ingelogde, actieve beheerder met ingestelde MFA. Kies **Publiceren** om het item zichtbaar te maken.

Bij bestaande gepubliceerde inhoud heet de knop **Opslaan en publiceren**. De wijziging is na opslaan direct zichtbaar. Er is nog geen afzonderlijke revisie naast de liveversie. Wil je een item eerst afschermen, kies dan **Terug naar concept**. **Archiveren** haalt inhoud eveneens uit de publieke website.

Verwijderde inhoud verdwijnt uit de site en blijft via het prullenbakfilter in de lijst herstelbaar. Afbeeldingen worden afzonderlijk verwijderd en kennen geen prullenbak. De applicatie blokkeert het verwijderen van een afbeelding zolang een record ernaar verwijst, ook als dat record in de prullenbak staat.

Bij het intrekken van een project verdwijnen ook de detailpagina, oude doorverwijzingen, sitemapvermelding en bijdrage aan het projectaantal. Een verborgen organisatie verbergt haar naam en logo bij gekoppelde cv-items; het cv-item zelf heeft een eigen publicatiestatus.

## Je uitstraling veranderen

Open het bestaande record onder **Profiel & vormgeving**. Het profiel heeft vier tabbladen:

1. **Mijn profiel:** naam, functietitel, introductie, biografie, foto, eigenschappen en interesses. Gebruik een lege regel tussen alinea’s in je biografie.
2. **Homepage:** begroeting boven je naam, koppen bij interesses, werkervaring en eigen initiatieven, persoonlijke noot, optionele hoofdafbeelding en haltes in je loopbaanverhaal. Naam, functietitel en introductie komen uit **Mijn profiel**. De hoofdafbeelding heeft voorrang op de portretfoto; zonder beide verschijnt een persoonlijke tekstkaart. De eerdere slogan en technische labels worden niet meer gebruikt.
3. **Kleuren & stijl:** kies Nacht of Licht, daarnaast Paddock, Graphite of Electric en eventueel eigen hexkleuren. Nacht is de standaard, ook voor bestaande profielen. Eigen achtergrond- en tekstkleuren blijven voorrang houden; maak ze leeg om de basiskleuren van Nacht of Licht te gebruiken. Hier kies je ook je beeldmerk en schakel je beweging, persoonlijke verhalen, development en organisatielogo’s op home in of uit.
4. **Contact & footer:** voeg bewust openbare links toe en wijzig de footerteksten. Gebruik `https://` voor profielpagina’s en `mailto:naam@voorbeeld.nl` voor een openbaar e-mailadres. Je beheeradres wordt niet automatisch gepubliceerd.

De vernieuwde contactpagina toont je openbare links als duidelijke contactregels met een icoon, label en optionele toelichting. Onder **Contact & footer** kun je ook de kop en introductie boven deze regels wijzigen. Zonder links verschijnt een rustige melding met een route naar je cv; er wordt geen adres of bereikbaarheid verzonnen. De profielkaart gebruikt je naam, functie, foto of beeldmerk en gepubliceerde cv-/over-mijroutes. De deelknop onthult geen contactgegevens en verschijnt alleen als JavaScript werkt.

Onder ditzelfde tabblad staan de **GitHub-gebruikersnaam** en de schakelaar voor openbare repositorycijfers. GitHub wordt automatisch als profiel-link toegevoegd; een al aanwezige identieke link wordt niet herhaald. Na opslaan kun je met **GitHub bijwerken** een meting ophalen. Echte cijfers verschijnen pas na succes. De contactpagina behoudt gewone routes naar je achtergrond en cv, zonder een aparte werkgeverssectie. Zie [Statistieken](statistieken.md) voor definities, cron en foutafhandeling.

De tooltip bij **GitHub bijwerken** toont het laatste succes en eventueel de foutcategorie. Bij een mislukte eerste meting worden geen nultellingen gepubliceerd; een werkelijk lege repositoryset kan na een geslaagde meting wel nul opleveren. Een verversactie gebruikt alleen de opgeslagen instellingen, dus sla wijzigingen eerst op.

De bewegingsschakelaar zet de speelse animaties sitebreed uit. Een bezoeker die minder beweging heeft ingesteld, krijgt ook bij een ingeschakelde schakelaar een rustige weergave. De kleurkiezer controleert de invoervorm; controleer bij eigen kleuren zelf de leesbaarheid van tekst en knoppen.

De zon-/maanknop rechts in de publieke header wisselt tussen licht en donker. De keuze wordt alleen in die browser opgeslagen en blijft bij navigatie en herladen behouden. Je portaalinstelling bepaalt de standaard voor bezoekers zonder eigen keuze. Een beheervoorbeeld gebruikt die portaalstandaard, zodat een eerder opgeslagen bezoekersvoorkeur je ontwerpcontrole niet verbergt. Zonder JavaScript blijft de standaardweergave werken en wordt de knop niet getoond.

Eigen achtergrond- en tekstkleuren gelden voor de standaardweergave die je in het portaal hebt gekozen. De andere bezoekersweergave gebruikt de passende basiskleuren van het schema; de eigen accentkleur geldt voor beide. Zo wordt een lichte eigen achtergrond niet onbedoeld met lichte tekst gecombineerd bij het omschakelen. Het Filament-beheer heeft zijn eigen weergave-instellingen; de publieke knop verandert geen beheerrechten of CMS-inhoud.

De interessekaarten op home komen uit de gepubliceerde **persoonlijke verhalen** onder **Cv, pagina’s & cijfers**. Met **Korte titel op de interessekaart** kies je een compacte kaarttitel; leeg gebruikt Autosport, Technologie of Karakter. De gewone titel en beschrijving blijven voor het uitgebreidere verhaal gelden. De kaartlink opent op Over mij meteen de juiste interesse. Het compacte online cv en de witte printversie gebruiken dezelfde gepubliceerde gegevens.

## Bedrijfslogo’s toevoegen

1. Open **Afbeeldingen → Aanmaken**. Upload je logo en vul een herkenbare titel en alternatieve tekst in, bijvoorbeeld “Logo NS”.
2. Open de organisatie onder **Organisaties** en kies deze afbeelding bij het logo. Sla op en publiceer de organisatie.
3. Open een functie onder **Cv, pagina’s & cijfers** en selecteer de organisatie. Sla op en publiceer het cv-item.

Hetzelfde organisatierecord kan bij meerdere functies worden gebruikt. Een wijziging aan het logo werkt daardoor overal door. Organisaties uit je gepubliceerde cv kunnen ook op de homepage staan. Zonder afbeelding gebruikt de site de organisatienaam. Zeven logo’s zijn vanuit officiële bronnen toegevoegd; zie [Bedrijfslogo’s](bedrijfslogos.md). Overige logo’s voeg je zelf toe via de bibliotheek. Het NS-logo wordt ook bij NS Leercentrum gebruikt en verschijnt op home eenmaal.

Bij een wit logo kies je in **Afbeeldingen → Achtergrond bij gebruik als logo** voor **Donkerblauw**. Dezelfde instelling werkt op home, het cv en de printversie. De bron-/makervelden zijn voor je eigen administratie; het bijschrift wordt bij projectbeelden getoond.

De bibliotheek accepteert **JPEG, PNG en WebP tot 5 MB en 16 miljoen pixels**. Afbeeldingen worden opnieuw opgebouwd als WebP, met maximaal 2400 pixels aan de langste zijde. Transparantie blijft behouden en metadata wordt verwijderd. SVG, PDF en andere bestandstypen worden niet als afbeelding geaccepteerd.

Bestanden staan privé buiten `public/`. Een publieke afbeeldingslink werkt alleen zolang de afbeelding aan gepubliceerde inhoud gekoppeld is. Gebruik de bibliotheek voor foto's, logo’s en projectbeelden; de bron-cv’s worden niet als download toegevoegd.

## Cv, diploma’s en persoonlijke verhalen

Kies bij een nieuw onderdeel eerst de **soort**. De bijbehorende velden verschijnen dan vanzelf. Bij een ervaring vul je functie, organisatie, locatie, periode, samenvatting en werkzaamheden in. Elk werkzaamheidspunt is een apart item. Het online cv en de printweergave gebruiken dezelfde records.

Periodes zijn bewust redactionele tekst, bijvoorbeeld “Maart 2026 — heden”. Ze worden niet automatisch uit een begin- of einddatum berekend. Gebruik de volgordevelden of de herschikfunctie in de lijst om de volgorde te bepalen; filter de lijst eerst op soort als je één sectie wilt ordenen.

Opleidingen en trainingen krijgen een eigen behaaldstatus. Een ontbrekende uitgever of datum mag leeg blijven. Vaardigheden bestaan uit groepen met losse vaardigheden; talen hebben een afkorting en een beschrijving van het niveau. Er worden geen percentages berekend.

De drie persoonlijke verhalen gebruiken de sleutels `world:circuit`, `world:code` en `world:character`. Je kunt hun titel, beschrijving, korte label en afbeelding aanpassen. Een ingetrokken verhaal verschijnt niet meer als keuze. De bijbehorende SVG-illustraties blijven vaste componenten.

Het eerdere cv blijft leidend; `Profile.pdf` is uitsluitend gebruikt voor aanvullingen. De bronkeuzes en nog ontbrekende gegevens staan in [Cv-verwerking](cv-verwerking.md).

## Paginateksten en zichtbaarheid

Filter **Cv, pagina’s & cijfers** op **Pagina** en open de gewenste vaste pagina: home, over mij, cv, projecten, development, contact of privacy. Pas titel, introductie, grote kop, kleine bovenregel en extra tekstonderdelen aan. Bij de homepage staat de grote introductiekop onder **Profiel & vormgeving → Homepage**. Een eigen pagina-afbeelding kan de standaardillustratie aanvullen of vervangen.

Intrekken van een pagina verbergt de bijbehorende route, navigatielink en sitemapvermelding. Het gaat om zeven vaste routes: een extra willekeurige pagina aanmaken maakt geen nieuwe URL. Gebruik de bestaande records. Development kan daarnaast via de profielschakelaar verborgen worden.

## Projecten en statistieken

Een project heeft een **projectstatus** zoals “In ontwikkeling” en daarnaast een **publicatiestatus**. Een nog niet afgerond project kan dus wel in je portfolio gepubliceerd zijn. Stel je rol, beschrijving, technieken, hoofdstukken en links in. Kies een cover en eventueel extra afbeeldingen uit de bibliotheek. Zonder cover gebruikt de kaart een gekozen ontwerpillustratie.

Het formulier is verdeeld over **Het project**, **Het verhaal**, **Afbeeldingen** en **Weergave**. Onder Afbeeldingen voeg je beelden toe en sleep je ze in de juiste volgorde. Alt-teksten en bijschriften wijzig je bij de afbeelding in de bibliotheek. Bezoekers kunnen de beelden groot bekijken, met pijltjestoetsen wisselen en met Escape sluiten. De projectverhalen zijn verwerkt op basis van je toelichting; zie [Projectinhoud](projectinhoud.md).

**Uitlichten op de homepage** bepaalt of het project tussen de uitgelichte projecten mag staan. De homepage toont maximaal drie daarvan. Wijzig je het projectadres, dan blijft het oude adres doorverwijzen zolang het project gepubliceerd is. Een eerder gebruikt adres is gereserveerd voor die doorverwijzing.

Een eigen cijfer voeg je toe als soort **Statistiek**. Waarde, bron en peildatum zijn verplicht; eenheid en bronlink zijn optioneel. Gepubliceerde cijfers staan op Development. Het aantal projecten en initiatieven wordt automatisch uit gepubliceerde projecten berekend. De kalender blijft een zichtbaar gemarkeerde demonstratie tot de GitHub-koppeling is gebouwd; er worden nu geen echte GitHub-cijfers opgehaald.

## Eerste inhoud opnieuw importeren

De lokale setup importeert de eerder geselecteerde cv- en projectinhoud eenmalig. Voor een bestaande checkout met nieuwe migrations:

```powershell
.\scripts\php.ps1 artisan migrate
.\scripts\php.ps1 artisan app:import-portfolio
```

De import voegt ontbrekende records toe. Hij overschrijft geen wijzigingen en herstelt geen verwijderde records. Je hoeft na het aanpassen van inhoud geen frontendbuild uit te voeren.

De import vult ook lege organisatielogo’s aan vanuit de meegeleverde bronnen; een gekozen eigen logo blijft behouden. Voor de eerdere project-startteksten is afzonderlijk `artisan app:import-project-details` beschikbaar. Dit vervangt alleen velden die nog exact overeenkomen met de oorspronkelijke teksten en behoudt je eigen redactie en publicatiestatus.

De database en eigen afbeeldingen staan buiten Git. Bij een verse checkout ontbreken je latere wijzigingen en uploads totdat je een back-up terugzet. De privéfoto uit de cv-import staat alleen op de huidige ontwikkelmachine; upload je foto op een nieuwe omgeving via het portaal. De importklassen zijn een startbron, geen synchronisatie met je documenten.

## Nog vóór livegang

De site is lokaal gecontroleerd en staat nog op `noindex, nofollow`. De Vimexx-accountcontrole, MySQL/MariaDB-test op de host, staging, back-upherstel en definitieve metadata horen bij de volgende oplevering. De [hostinghandleiding](hosting-en-deployment.md) beschrijft die stappen.
