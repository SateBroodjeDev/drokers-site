# Besluiten en open punten

[Terug naar de README](../README.md)

Dit register maakt onderscheid tussen gegevens van de eigenaar en ontwerpkeuzes. De lokale website en het beheerportaal zijn gebouwd, inclusief inhoudsbeheer, privémedia en instelbare vormgeving. Hostingdeployment en de automatische GitHub-koppeling volgen nog.

## Bevestigde uitgangspunten

| ID | Uitgangspunt | Herkomst |
| --- | --- | --- |
| U01 | Persoonlijke website en cv op `domenicrokers.nl` | Opdracht van de eigenaar |
| U02 | Werkervaring, ervaringen, diploma's, projecten en developmentstatistieken zichtbaar maken | Opdracht van de eigenaar |
| U03 | Inhoud beheren via een backendwebportaal | Opdracht van de eigenaar |
| U04 | Ontwikkeling in GitHub; deze map is voor dit nieuwe project | Bevestigd door de eigenaar |
| U05 | Bestaand Vimexx-webhostingpakket, door de eigenaar omschreven als het duurste pakket | Mededeling van de eigenaar; precieze pakketnaam onbekend |
| U06 | DNS via Cloudflare | Mededeling van de eigenaar; proxyinstellingen onbekend |
| U07 | `globetrotr.nl` is een te presenteren project | Voorbeeld van de eigenaar; inhoud nog aan te leveren |
| U08 | Eerder cv is leidend; `Profile.pdf` uitsluitend voor aanvullingen | Expliciete bronkeuze van de eigenaar na vergelijking van beide documenten |
| U09 | De groen/witte stijl past niet; veel meer persoonlijkheid en beheerbaarheid, inclusief bedrijfslogo’s | Laatste ontwerpverzoek van de eigenaar |
| U10 | GlobeTrotr als reisplatform en DutchUnitedRacing als simracecompetitie, met eigen rollen en technieken | Directe projecttoelichting van de eigenaar; verwerkt in [Projectinhoud](projectinhoud.md) |

## Ontwerpkeuzes

| ID | Voorstel | Motivering / gevolg |
| --- | --- | --- |
| B01 | Eén Laravel-applicatie | Website en portaal delen gegevens en publicatieregels; sluit aan op PHP-hosting |
| B02 | Laravel 13, Filament 5, Livewire 4 en Blade | Geïnstalleerd en vastgelegd in lockbestanden; Composer-platform PHP 8.3.0 |
| B03 | MySQL/MariaDB op Vimexx | Bestaande opslagmogelijkheden gebruiken; accountversie nog controleren |
| B04 | Eén actief beheeraccount en verplichte 2FA | Passend bij persoonlijk beheer; geen openbare registratie |
| B05 | Nederlands als eerste taal | Sluit aan op de opdracht; vertaling als latere uitbreiding |
| B06 | Beheer via `/admin` op hetzelfde domein | Geen aparte applicatie of apart beheerdomein nodig |
| B07 | Gestructureerde inhoudsformulieren | Cv en projecten blijven consistent; geen algemene paginabouwer |
| B08 | Handmatige en interne statistieken eerst | De eerste release heeft bruikbare cijfers zonder externe koppeling |
| B09 | GitHub-statistieken in een vervolgfase | Account en meetdefinities bewust instellen; site blijft onafhankelijk van live API-verzoeken |
| B10 | Printbaar cv via de browser | Eén inhoudsbron; geen serverafhankelijke PDF-generator voor de eerste release |
| B11 | Contact via links | Geen berichtopslag of mailafhandeling nodig in de eerste release |
| B12 | Private originele media, gecontroleerde publieke varianten | Conceptbestanden blijven buiten de openbare documentroot |
| B13 | Afzonderlijke staging en handmatig gestarte releases | Hosting en functies controleren voordat productie wordt bijgewerkt |
| B14 | Paddock als standaard, met instelbare schema’s | Donkerblauw, oranje en koel lichtgrijs; Graphite/Electric en eigen kleuren via het portaal. Vervangt de afgewezen groene richting |
| B15 | SQLite voor de eerste lokale basis | Geen databaseserver beschikbaar; CI voorbereid op MySQL, hostingcontrole en staging blijven nodig |
| B16 | Projectgebonden Windows-tools | PHP 8.4.26 en Composer 2.10.3 in genegeerde `.tools/`; geen systeeminstallatie of permanente PATH-wijziging |
| B17 | Publieke website leest gepubliceerde CMS-records | Geen fictieve cv-feiten; kalender blijft gemarkeerd als demonstratie tot de GitHub-koppeling bestaat |
| B18 | Persoonlijke richting “Van circuit naar code” | Door Domenic aangeleverde eigenschappen, marshalrol en interesses bepalen tekst, circuitillustratie en bewegende details |
| B19 | Geselecteerd cv en echte foto in het publieke prototype | Online en print delen één bron; originele PDF, adres, geboortedatum, telefoon en e-mail uit het bronbestand blijven buiten publieke inhoud |
| B20 | Eerder cv leidend, LinkedIn-export als aanvullende bron | Bevestigd door de eigenaar; cv-functies en perioden behouden, extra ervaring, talen en DutchUnitedRacing uit het profiel verwerkt |
| B21 | Gedeelde organisaties met bedrijfslogo’s | Eén upload en organisatiekoppeling voor meerdere functies, opleidingen en de homepage; tekstnaam als er geen logo is |
| B22 | Compacte contentrecords en expliciete startimport | Cv, pagina’s, verhalen en handmatige cijfers delen publicatiegedrag; import overschrijft bestaande redactie niet |
| B23 | Vaste componenten met instelbare inhoud en uitstraling | Profiel, foto’s, kleuren, kopstijl, beweging en secties zijn beheerbaar; geen vrije HTML- of CSS-editor |

Een voorstel aanpassen vraagt geen nieuwe architectuur vanaf nul. Werk het relevante document en dit register bij zodat toekomstige ontwikkeling dezelfde uitgangspunten gebruikt.

## Open punten en wanneer ze nodig zijn

| ID | Gegeven of keuze | Nodig vóór | Voorgestelde aanpak |
| --- | --- | --- | --- |
| O01 | Exact pakket, web-PHP en platformcontrole | Eerste stagingdeployment | Host en CLI-PHP 8.3.27 met benodigde modules door eigenaar gecontroleerd; web-runtime, Composer en GD-WebP nog controleren |
| O02 | Databaseversie, cronwerking en releaselinks | Eerste stagingdeployment | Geslaagde SSH-login aangeleverd; cron staat ON. Database, releasepaden, sleutelauthenticatie en taakuitvoering nog controleren |
| O03 | Cloudflare-proxy, TLS en bestaande DNS-records | Eerste domeinkoppeling/deployment | Bestaande configuratie vastleggen voordat die wordt gewijzigd |
| O04 | Controle van profiel en actuele gegevens | Inhoudsoplevering | Naamkeuze Domenic bevestigd; huidige functie en cv-inhoud verwerkt, wijzigingen vóór livegang controleren |
| O05 | Eventuele grotere profielfoto | Visuele afwerking | Eigen foto uit het cv gebruikt op 300 × 300 pixels; een groter origineel is een mogelijke verbetering |
| O06 | Laatste broncontrole cv | Eerste livegang | Inhoud staat nu in het portaal; uitgever Trackside Official en actuele “heden”-functies controleren |
| O07 | Echte beelden van GlobeTrotr en DutchUnitedRacing | Eerste livegang | Doel, rollen en technieken zijn verwerkt; projectbeelden en eventuele publieke DutchUnitedRacing-link nog aanvullen |
| O08 | Publieke contactgegevens en beschikbaarheid | Eerste livegang | Alleen gekozen velden tonen; beheer-e-mail blijft privé |
| O09 | GitHub-gebruikersnaam en gewenste statistieken | GitHub-fase | Openbare gegevens als standaard; toegangsrechten apart bepalen |
| O10 | Back-upbestemming en toegang voor herstel | Eerste livegang | Externe versleutelde kopie en hersteltest inrichten |
| O11 | Repositoryzichtbaarheid en codelicentie | Openbaar delen van de broncode | Eigenaar bepaalt; geen licentie veronderstellen |
| O12 | Databasetest tegen de werkelijke hostversie | Eerste stagingdeployment | Lokale basis werkt met SQLite; MySQL-CI en later staging valideren het hostingdoel |

Deze punten blokkeren lokaal inhoudsbeheer niet. Projectdetails, eigen logo’s, foto’s en contactlinks kunnen nu via het portaal worden aangevuld. Toegang en secrets worden via geschikte beveiligde configuratie geregeld, niet in dit document opgenomen.

## Afgehandelde open punten

| ID | Besluit | Uitkomst |
| --- | --- | --- |
| O13 | Leidende bron bij cv/LinkedIn-verschillen | Eigenaar kiest het eerdere cv; `Profile.pdf` alleen voor aanvullingen. Vastgelegd bij U08 en in [Verwerking van het cv](cv-verwerking.md). |

## Externe bronnen

De technische richting is op 17 oktober 2026 gecontroleerd aan de hand van officiële documentatie. Deze bronnen beschrijven leveranciersmogelijkheden, geen inzage in het account:

- [Vimexx webhosting](https://www.vimexx.nl/webhosting) en [Laravel bij Vimexx](https://www.vimexx.nl/help/hoe-installeer-ik-het-laravel-framework-op-mijn-website).
- [Vimexx cronjobs](https://www.vimexx.nl/help/hoe-maak-ik-een-cronjob-aan).
- [Laravel 13 releases](https://laravel.com/framework/docs/13.x/releases), [deployment](https://laravel.com/framework/docs/13.x/deployment) en [databases](https://laravel.com/framework/docs/13.x/database).
- [Filament 5 installatie](https://filamentphp.com/docs/5.x/introduction/installation) en [MFA](https://filamentphp.com/docs/5.x/users/multi-factor-authentication).
- [Cloudflare Full (strict)](https://developers.cloudflare.com/ssl/origin-configuration/ssl-modes/full-strict/).
- [GitHub repositorytalen](https://docs.github.com/en/rest/repos/repos#list-repository-languages) en [bijdragen](https://docs.github.com/en/graphql/reference/users#contributionscollection).

Controleer versies en vereisten opnieuw bij installatie of een wijziging van hosting. Gemaakte accountcontroles krijgen later een korte datum en uitkomst, zonder credentials.
