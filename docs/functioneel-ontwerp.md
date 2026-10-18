# Functioneel ontwerp

[Terug naar de README](../README.md)

**Huidige status:** de onderstaande kernfuncties zijn lokaal gebouwd. Profiel, vormgeving, organisaties met logo’s, cv, projecten, media, pagina’s en handmatige cijfers zijn beheerbaar. Vóór livegang blijven definitieve metadata, staging en hostingherstel open; GitHub-statistieken zijn een vervolgfase. De praktische bediening staat in [Je website beheren](beheerportaal.md).

## Publieke pagina's

| Route | Inhoud en gedrag |
| --- | --- |
| `/` | Persoonlijke introductie, organisaties, maximaal drie uitgelichte projecten, persoonlijke verhalen, cv-/developmentroutes en contactactie |
| `/over-mij` | Biografie, drijfveren, werkwijze en optionele interesses |
| `/cv` | Werkervaring, opleidingen, diploma's/certificaten en vaardigheden; knop naar printweergave |
| `/cv/print` | Dezelfde gepubliceerde cv-inhoud in een rustige printopmaak; niet apart indexeren |
| `/projecten` | Gepubliceerde projecten, uitgelichte projecten eerst; zoeken en filter op techniek |
| `/projecten/{slug}` | Aanleiding, eigen rol, aanpak, resultaten indien bekend, afbeeldingen, technieken en links |
| `/development` | Statistieken met bron en peildatum; later GitHub-activiteit |
| `/contact` | Korte uitnodiging en de gekozen e-mail- en profiel-links |
| `/privacy` | Feitelijke uitleg over de gegevensverwerking van de uiteindelijke implementatie |
| `/sitemap.xml` | Alleen ingeschakelde publieke pagina's en gepubliceerde projectpagina's |
| `/robots.txt` | Verwijzing naar de sitemap; uitsluiting van beheer en previews als aanvullende indexeringsinstructie |

De primaire navigatie bevat Home, Over mij, Cv, Projecten, Development en Contact, voor zover de pagina gepubliceerd is. Een ingetrokken pagina verdwijnt uit navigatie en sitemap en geeft publiek een 404. Development heeft daarnaast een zichtbaarheidsschakelaar in het profiel. Een lege pagina wordt niet automatisch ingetrokken: de eigenaar bepaalt zelf of bijvoorbeeld de kalenderdemonstratie zichtbaar mag blijven.

## Cv

Werkervaring toont functie, gedeelde organisatie met optioneel logo, periode, locatie, beschrijving en werkzaamheden. Periodes zijn redactionele tekst, inclusief “heden” indien van toepassing. Opleidingen en trainingen tonen titel, organisatie, periode, toelichting en behaaldstatus. Afzonderlijke verloopdatums en verificatielinks zijn nog geen beheerfuncties.

Vaardigheden worden gegroepeerd, bijvoorbeeld in development, tools en overige vaardigheden. Een korte ervaringsomschrijving is optioneel. Procentuele vaardigheidsmeters worden niet gebruikt: die hebben zonder meetmethode weinig betekenis.

De printweergave gebruikt dezelfde gegevens en volgorde. Navigatie, decoratie en interactieve filters worden bij afdrukken verborgen. De browser maakt de PDF; er draait hiervoor geen PDF-service op de server.

## Projecten

Ieder project heeft een naam, unieke slug, korte omschrijving, langere toelichting, eigen rol en optionele onderdelen voor probleem, aanpak en resultaat. De eigenaar kan een cover en aanvullende afbeeldingen toevoegen, technieken koppelen en website- of repositorylinks invullen.

Projectstatus en publicatiestatus zijn afzonderlijke gegevens. Een project kan bijvoorbeeld “In ontwikkeling” zijn en toch gepubliceerd worden. Projectstatus is nu een vrij in te vullen label; technieken zijn eigen tags. Er is geen verplichte koppeling aan de cv-vaardigheidsgroepen.

Een overzichtsfilter gebruikt deelbare URL-parameters en werkt zonder externe API. Bij nul resultaten verschijnt een duidelijke melding met een actie om de filters te wissen. Een concept of gearchiveerd project geeft via zijn publieke URL een 404.

## Beheerportaal

Het portaal staat op `/admin`. De eerste beheerder wordt via een servercommando aangemaakt. Openbare registratie staat uit. Toegang vereist een actief beheeraccount en tweefactorauthenticatie met een authenticator-app. Herstelcodes en een gedocumenteerde serverprocedure voorkomen verlies van toegang.

| Beheeronderdeel | Mogelijkheden |
| --- | --- |
| Dashboard | Aantallen concepten/gepubliceerde projecten, ontbrekende inhoud en later de status van statistieksynchronisatie |
| Profiel & vormgeving | Naam, functietitel, introductie, biografie, foto, kleurenschema, eigen kleuren, kopstijl, beweging en secties |
| Organisaties | Gedeelde werkgever/opleider/vereniging, website en bedrijfslogo |
| Contactlinks | E-mail, GitHub, LinkedIn en andere bewust gekozen links; aan/uit en volgorde |
| Werkervaring | Aanmaken, bewerken, ordenen, publiceren, verbergen en archiveren |
| Opleidingen | Instelling, opleiding, periode, status, toelichting en publicatie |
| Diploma's/certificaten | Uitgever, periode, toelichting en behaaldstatus |
| Vaardigheden | Naam, categorie, optionele toelichting, volgorde en zichtbaarheid |
| Projecten | Inhoud, projectstatus, afbeeldingen, vaardigheden, links, uitlichten en publicatie |
| Statistieken | Handmatig cijfer, eenheid, bron, meetperiode/peildatum en zichtbaarheid; intern berekende tellers inzien |
| Afbeeldingen | Uploaden, titel en alt-tekst toevoegen, privé bekijken en ongebruikte bestanden verwijderen; gekoppelde bestanden worden beschermd |
| Pagina-instellingen | Vaste paginatitels, intro's, koppen, afbeeldingen, extra tekstsecties en publicatie; definitieve deelmetadata volgt vóór livegang |

Bij verplichte velden verschijnt de fout naast het veld. Niet-opgeslagen wijzigingen krijgen een waarschuwing bij verlaten. Geslaagde acties krijgen een korte bevestiging. De site gebruikt Nederlandstalige labels en datumnotatie.

## Publiceren en preview

Herhaalbare inhoud, zoals werkervaring, opleidingen, certificaten, vaardigheden, projecten en statistieken, heeft de toestanden `draft`, `published` en `archived`.

1. Een nieuw item begint als concept.
2. De beheerder kan een opgeslagen concept via een ingelogde preview bekijken.
3. “Publiceren” valideert de inhoud en maakt die zichtbaar op alle relevante publieke plekken.
4. Bij een gepubliceerd item heet de opslagactie “Opslaan en publiceren”; wijzigingen worden daarna direct zichtbaar.
5. “Terug naar concept” trekt het hele item in. “Archiveren” verbergt het en bewaart de gegevens in het portaal.

Er is in de eerste release geen afzonderlijke conceptversie naast een bestaande gepubliceerde versie. Ook zijn er geen geplande publicaties of volledige versiehistorie. De interface maakt de gevolgen van opslaan en intrekken expliciet. Gepubliceerde profiel- en pagina-instellingen gebruiken eveneens een expliciete opslagactie, maar krijgen geen apart revisiesysteem.

Voor iedere publieke query geldt dezelfde publicatiefilter. Previewroutes controleren de beheersessie, krijgen `noindex` en worden niet gecachet. Een moeilijk te raden previewlink vervangt authenticatie niet.

Inhoud verwijderen gaat via de prullenbak; herstellen is mogelijk. Afbeeldingen hebben geen prullenbak en kunnen niet worden verwijderd zolang inhoud ernaar verwijst. De site leest inhoud per verzoek opnieuw; er is nog geen aanvullende contentcache.

## Statistieken

De eerste versie biedt handmatige statistieken en berekende tellingen uit gepubliceerde gegevens. Een handmatig cijfer heeft altijd een beschrijving, bron en peildatum of meetperiode. Een nul is een gemeten nul; ontbrekende gegevens worden als ontbrekend behandeld.

De vervolgfase voegt GitHub-statistieken toe. De eigenaar kiest wat zichtbaar is. API-fouten worden in het portaal gemeld; bezoekers zien de laatst geslaagde meting met datum of een rustige melding wanneer er nog geen gegevens zijn. Details staan in [Statistieken](statistieken.md).

## Contact, media en persoonlijke gegevens

Contact verloopt in de eerste release via links. Een publiek e-mailadres is optioneel; het privé-e-mailadres van het beheeraccount wordt nooit automatisch gepubliceerd. Telefoonnummer, woonadres en geboortedatum worden niet standaard gevraagd.

Afbeeldingen krijgen serverzijdige type-, grootte- en afmetingencontrole: JPEG, PNG en WebP tot 5 MB en 16 miljoen pixels. Ze worden opnieuw opgebouwd als WebP tot 2400 pixels aan de langste zijde. SVG en uitvoerbare bestanden worden niet toegestaan. Persoonlijke scans van diploma's zijn geen vereiste en worden niet standaard openbaar gemaakt.

Conceptmedia wordt alleen aan de ingelogde beheerder geleverd. Voor openbare projectafbeeldingen geldt dat reeds gedeelde of gecachete kopieën na intrekken niet overal kunnen worden teruggehaald. Vertrouwelijke bewijsstukken horen daarom niet in publieke projectmedia.

## Acceptatiecriteria

| ID | Controleerbaar resultaat |
| --- | --- |
| F01 | Gepubliceerde profielgegevens en contactlinks verschijnen op de bedoelde pagina's; lege optionele velden verdwijnen netjes. |
| F02 | Een nieuwe werkervaring kan zonder codewijziging worden aangemaakt, geordend en gepubliceerd; periode en “heden” volgen de ingevoerde tekst. |
| F03 | Opleidingen, certificaten en vaardigheden zijn afzonderlijk te beheren en te verbergen. |
| F04 | Een project krijgt een unieke URL, cover, tekst, technieken en links; filters zijn ook via een gedeelde URL reproduceerbaar. |
| F05 | Een concept, gearchiveerd of verwijderd item is afwezig uit publieke pagina's, tellers en sitemap; directe project-URL geeft 404. |
| F06 | Ingelogde preview werkt; een uitgelogde bezoeker krijgt geen conceptinhoud of conceptmedia. |
| F07 | Beheer is ontoegankelijk zonder actief beheeraccount en afgeronde 2FA; herstelcodes zijn eenmalig bruikbaar. |
| F08 | Een handmatige statistiek toont bron en datum; een projectteller telt alleen gepubliceerde, niet-verwijderde projecten. |
| F09 | Publieke inhoud werkt zonder een geconfigureerde GitHub-koppeling. |
| F10 | Het printbare cv bevat de actuele gepubliceerde gegevens en blijft leesbaar bij opslaan als PDF. |
| F11 | Primaire routes zijn bruikbaar op 360 px breedte, op desktop en met alleen een toetsenbord. |
| F12 | Paginatitels, canonieke URL's, sitemap en deelmetadata bevatten uitsluitend publieke inhoud. |
| F13 | Afgekeurde uploads worden geweigerd; toegestane afbeeldingen verschijnen met passende alt-tekst. |
| F14 | Na publiceren of intrekken ziet een nieuwe bezoeker de juiste inhoud en tellingen, zonder handmatige cachehandeling. |
| F15 | Een ingetrokken optionele pagina verdwijnt uit navigatie en sitemap en is ook via de directe URL niet publiek beschikbaar. |

Beveiliging, deployment en herstel krijgen aanvullende technische controlepunten in de bijbehorende documenten.
