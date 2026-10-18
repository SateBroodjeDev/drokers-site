# Bedrijfslogo’s en beeldbronnen

[Terug naar de README](../README.md) · [Beheerhandleiding](beheerportaal.md)

## Toegevoegde logo’s

Zeven logo’s zijn op 17 oktober 2026 vanaf officiële websites opgehaald, gecontroleerd en lokaal opgenomen. Ze dienen ter herkenning van organisaties uit het cv en worden niet gepresenteerd als klantlogo’s, aanbevelingen of partnerships.

| Logo | Gekoppelde organisatie | Officiële bron | Weergave |
| --- | --- | --- | --- |
| NS | Nederlandse Spoorwegen en NS Leercentrum | [NS](https://www.ns.nl/) | Donkerblauw op licht; één gedeeld bestand |
| TSC | TSC Crowd Management | [TSC](https://www.tsc.nl/) | Horizontaal logo op licht |
| Qarin | Qarin B.V. | [Perspagina met PNG-download](https://qarin.nl/pers/) | Volledig icoon, woordmerk en tagline behouden |
| PROFI-SEC | PROFI-SEC Security & Crowdmanagement | [PROFI-SEC](https://profi-sec.nl/) | Wit woordmerk op donkerblauw |
| Efteling | Efteling | [Efteling](https://www.efteling.com/nl) | Rood/gouden logo op licht |
| SVPB | SVPB | [SVPB](https://www.svpb.nl/) | Vierkante officiële variant op licht |
| Railcenter | Railcenter | [Railcenter](https://www.railcenter.nl/) | Donkerblauw voor de witte onderdelen |

Download-URL’s, controledatum, voorbereiding en SHA-256 van ieder webbestand staan in [sources.json](../resources/brand-assets/sources.json). De complete Qarin-variant volgt de samenstelling die de officiële perspagina beschrijft. Er zijn geen nieuwe bedrijfslogo’s getekend of met AI gegenereerd.

Kleuren, verhoudingen en de volledige logo’s zijn behouden. De NS- en SVPB-SVG’s zijn technisch gerasteriseerd; alle bestanden zijn als WebP voorbereid met maximaal 640 pixels aan de langste zijde. Bij Efteling is uitsluitend overtollige transparante buitenruimte verkleind, met ruimte rondom het volledige merkbeeld.

Voor overige organisaties blijft de tekstnaam beschikbaar. Coolblue en VodafoneZiggo hadden geen geschikte rechtstreeks toegankelijke officiële download in deze onderzoeksronde. Voor OCA en DutchUnitedRacing is geen onbevestigd logo overgenomen. Die bestanden kunnen later via de bibliotheek worden toegevoegd.

## Opslag en vervanging

De voorbereide bronbestanden staan onder `resources/brand-assets/organizations/`, buiten de openbare documentroot. `OrganizationLogoSeeder` kopieert ze via de normale afbeeldingscontrole naar de private mediabibliotheek en koppelt alleen organisaties met een leeg logoveld. Een al gekozen eigen logo blijft behouden. De bronbestanden staan in Git; lokale uploads en de database niet.

De expliciete portfolio-import voert deze logo-aanvulling uit. Setup, tests en de publieke website doen geen netwerkverzoeken voor logo’s. Een herhaalde import hergebruikt bestaande bronrecords. Een bewust leeggemaakt logoveld kan bij die import opnieuw aangevuld worden; gebruik voor dagelijkse wijzigingen het portaal.

Onder **Afbeeldingen** kun je het logo vervangen, een bronvermelding bewaren en **Achtergrond bij gebruik als logo** kiezen. Met **Donkerblauw** blijven witte logo’s zichtbaar op home, het online cv en in de printversie. De bronlink is administratie in het portaal en wordt niet automatisch als contact- of projectlink gepubliceerd.

De homepage toont maximaal zeven verschillende gekoppelde logo’s uit gepubliceerde cv-inhoud. Zonder logo’s worden organisatienamen gebruikt. Het gedeelde NS-bestand verschijnt op home eenmaal. Intrekken van een organisatie verbergt haar naam en logo; het bestand blijft alleen publiek bereikbaar als het elders bewust aan gepubliceerde inhoud hangt.
