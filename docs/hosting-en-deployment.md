# Hosting en deployment

[Terug naar de README](../README.md)

## Bevestigd en nog te controleren

De eigenaar heeft een Vimexx-webhostingpakket en gebruikt Cloudflare voor DNS. Een aangeleverd DirectAdmin-overzicht bevestigt dat Shell Access (SSH), cronjobs, SSL en PHP op `ON` staan. De eigenaar heeft een geslaagde SSH-login en uitvoer van de CLI-runtime aangeleverd: PHP **8.3.27** via `/opt/alt/php83/usr/bin/php`. PHP 8.3 en 8.4 zijn beschikbaar. De exact geselecteerde web-PHP-versie, databaseversie, pakketnaam en proxy-instellingen zijn nog niet vastgesteld. “Het duurste pakket” wordt niet vertaald naar een veronderstelde pakketnaam of gegarandeerde resources.

### Accountoverzicht van de eigenaar

| Gegeven | Aangeleverd | Nog te verifiëren |
| --- | --- | --- |
| SSH | Geslaagde login door de eigenaar, serverprompt ontvangen | Accountrechten voor releasepaden, hostkeycontrole en sleutelauthenticatie |
| PHP | CLI 8.3.27; alias verwijst naar `/opt/alt/php83/usr/bin/php`; 8.3 en 8.4 beschikbaar; WebP-functie aanwezig | Geselecteerde webversie en platformcontrole van het releasepakket |
| Composer | Versie 2.6.5 via SSH bevestigd | Productieplatformvereisten van het daadwerkelijke pakket |
| Bestaande website | WordPress; eigenaar kiest vervanging op het hoofddomein na back-up | Back-up, releasepaden en documentroot controleren |
| CLI-modules | Onder meer gd, intl, mbstring, pdo_mysql, pdo_sqlite, curl, openssl, sodium, XML en zip aanwezig | Dezelfde vereisten in de web-runtime; daadwerkelijke WebP-verwerking |
| Database | Clientverbinding door eigenaar bevestigd; MariaDB 10.6.20-MariaDB-cll-lve; SHOW TABLES geeft geen tabellen | Laravel-verbinding, migraties en applicatiegedrag op MariaDB |
| Cron | ON | Uitvoerbaar PHP-pad en werking van een taak |
| SSL | ON | Geldig certificaat voor de gekozen staginghost |
| Varnish | ON in het accountoverzicht | Actieve caching per domein; beheer, previews en private media uitsluiten |

Accountinformatie en de terminalcontrole zijn door de eigenaar aangeleverd; de applicatie is nog niet op de host getest of uitgerold. Servernaam, accountnaam en IP zijn lokaal vastgelegd in de genegeerde map `.work/`. Het account bevat ook andere domeinen, databases en e-mail. Een testomgeving krijgt eigen paden, database en opslag. De nameservers in DirectAdmin bewijzen niet welke delegatie bij de domeinregistratie actief is; Cloudflare blijft het opgegeven DNS-uitgangspunt.

De controles `php -v`, `command -v php` en `php -m` zijn uitgevoerd. Het shellcommando `php` is een alias. Gebruik voor cron en deployment daarom het bevestigde absolute PHP-pad; een niet-interactieve taak hoeft deze alias niet te kennen. De [Vimexx-handleiding](https://www.vimexx.nl/help/ssh-toegang-inschakelen) vermeldt poort 7685. Hostkeycontrole en sleutelauthenticatie zijn nog niet als uitgevoerd bevestigd.

De SSH-welkomstmelding verbiedt permanent draaiende daemons. De beoogde applicatie gebruikt de gewone webserver en korte taken via cron. Er wordt op de host geen `artisan serve`, Vite-server of permanente queueworker gestart. Composer 2.6.5 en de WebP-functie zijn inmiddels bevestigd. Databaseversie, releasepaden, web-PHP en de daadwerkelijke afbeeldingsverwerking worden vóór livegang gecontroleerd. De eigenaar kiest vervanging van WordPress op het hoofddomein na een back-up; zie [Staging en releasepakketten](staging-en-releases.md) voor deze route en de optionele testomgeving.

[Vimexx vermeldt PHP, databases, Composer, SSH en cronjobs](https://www.vimexx.nl/webhosting) en heeft een [eigen handleiding voor Laravel met een koppeling naar de publieke map](https://www.vimexx.nl/help/hoe-installeer-ik-het-laravel-framework-op-mijn-website). Dat ondersteunt de gekozen richting. Werkelijke accountinstellingen moeten vóór de eerste deployment worden gecontroleerd.

## Hostingcontrole voor de bouwfase

| Controle | Benodigd resultaat |
| --- | --- |
| Pakket en account | Vastgelegd welk account en welke domeinmap gebruikt worden |
| PHP voor webverkeer | Een door de gekozen Laravel- en Filament-versies ondersteunde PHP-versie |
| PHP voor CLI en cron | Dezelfde geschikte versie en het absolute pad naar de juiste binary |
| PHP-extensies | Vereisten van Laravel, Filament en de gekozen afbeeldingsverwerking aanwezig |
| Composer | Geschikte Composer 2-versie; dependencies moeten op het doelplatform valideerbaar zijn |
| Database | Werkelijke MySQL/MariaDB-versie, ondersteuning, database en eigen applicatiegebruiker bekend |
| SSH | Sleutelauthenticatie mogelijk; werkelijke host, poort en toegangsrechten vastgelegd |
| Documentroot | Alleen Laravel `public/` bereikbaar via `public_html` of ingestelde documentroot |
| Symlinks en opslag | Releaselinks en gedeelde private opslag werken binnen het account |
| Cron | Werkende interval en looptijdlimiet bekend; scheduler daadwerkelijk getest |
| Uitgaand HTTPS | Toegang tot GitHub mogelijk voor de vervolgfase |
| HTTPS | Geldig certificaat voor de te gebruiken hostnamen en werkende vernieuwing |
| Back-up | Database en uploads herstelbaar; eigen back-up buiten dit hostingaccount geregeld |
| Staging | Afzonderlijke database, opslag, configuratie en toegangsbeperking mogelijk |

Een bruikbare PHP-versie in DirectAdmin betekent niet automatisch dat `php` via SSH dezelfde versie start. Controleer dit expliciet. De frameworkvereisten staan in [Laravel deployment](https://laravel.com/framework/docs/13.x/deployment) en [Filament installatie](https://filamentphp.com/docs/5.x/introduction/installation).

Als een noodzakelijke mogelijkheid ontbreekt, passen we eerst de hostingconfiguratie of releasemethode aan. Een andere provider, VPS of externe database is pas een nieuw besluit nadat de concrete beperking is vastgesteld.

## Omgevingen

| Omgeving | Doel | Gegevens |
| --- | --- | --- |
| Lokaal | Ontwikkelen op de eigen machine | Fictieve voorbeelden; eigen lokale configuratie |
| Staging | Bouw, beheer en deployment testen | Afzonderlijke testdata, testcredentials en opslag |
| Productie | Publieke website | Door de eigenaar gecontroleerde inhoud |

Voorgestelde staginghost: `staging.domenicrokers.nl`, als die beschikbaar en gewenst is. Beveilig de volledige omgeving met toegangsbescherming en geef `noindex` mee. `robots.txt` of een moeilijk te raden URL beschermt testgegevens niet tegen bezoekers. Productietokens en echte privégegevens worden niet standaard naar staging gekopieerd.

## Cloudflare en DNS

- `domenicrokers.nl` verwijst naar het door Vimexx opgegeven adres. Voeg alleen een AAAA-record toe als de host dat IPv6-adres daadwerkelijk ondersteunt.
- `www` verwijst naar dezelfde site; één permanente redirect brengt bezoekers naar `https://domenicrokers.nl`.
- Controleer bestaande MX-, SPF-, DKIM-, DMARC- en verificatierecords voordat DNS wordt gewijzigd. De website uitrollen is geen aanleiding om mailrecords te vervangen.
- SSH en SFTP gebruiken het werkelijke hostingadres of een geschikte DNS-only hostnaam.

Cloudflare kan alleen DNS verzorgen of ook verkeer proxyen. Bij gebruik van de proxy is **Full (strict)** de beoogde TLS-instelling, nadat op Vimexx een geldig passend certificaat is gecontroleerd. Cloudflare beschrijft de vereisten in de [documentatie voor Full (strict)](https://developers.cloudflare.com/ssl/origin-configuration/ssl-modes/full-strict/).

Publieke HTML wordt aanvankelijk niet extra aan de rand gecachet. Schakel geen algemene “Cache Everything”-regel in. Controleer ook eventuele Varnish-caching bij Vimexx. Beheer, Livewire-acties, previews en responses met persoonlijke sessiegegevens mogen niet als publieke inhoud worden opgeslagen. Versiegebonden CSS en JavaScript mogen wel lang gecachet worden.

## Mappen en releases

Voorkeursindeling, met accountafhankelijke absolute paden:

```text
/home/ACCOUNT/apps/drokers/
  releases/
    RELEASE_ID/             # Applicatiecode, vendor en gebouwde assets
  shared/
    .env                    # Serverconfiguratie, geen onderdeel van Git
    storage/                # Uploads, logs en andere blijvende runtimegegevens
  current -> releases/RELEASE_ID

/home/ACCOUNT/domains/domenicrokers.nl/
  public_html -> /home/ACCOUNT/apps/drokers/current/public
```

De precieze paden worden pas na accountcontrole ingevuld. `storage` en `.env` worden vanuit elke release aan de gedeelde locatie gekoppeld. De webserver moet naar `public/` wijzen; de projectroot mag nooit de documentroot zijn. Dit volgt ook uit de [Laravel-serverconfiguratie](https://laravel.com/framework/docs/13.x/deployment#server-configuration).

Bij de eerste inrichting wordt eerst vastgesteld of `public_html` bestaande bestanden bevat. Bestaande inhoud wordt veilig bewaard voordat een link wordt ingericht. De verwijdercommando's uit algemene hostinghandleidingen worden niet blind overgenomen.

Als releaselinks niet werken op het account, wordt een gecontroleerde uitrol met onderhoudsvenster uitgewerkt en eerst op staging getest. Uploaden van losse bestanden in een actieve applicatiemap is geen betrouwbare standaardrelease.

## Omgevingsconfiguratie

Er zijn een `.env.example` en `.env.staging.example` met uitsluitend lege of fictieve waarden. Productie gebruikt onder meer:

- Applicatieomgeving `production`, debug uit en de canonieke HTTPS-URL.
- Een eenmalig gegenereerde, blijvend bewaarde applicatiesleutel.
- Databasecredentials voor dit project en de afgesproken sessie- en cachedrivers.
- Private opslagpaden, logrotatie en cookie-instellingen.
- Eventuele SMTP-configuratie voor getest accountherstel.
- Pas in de GitHub-fase: accountnaam en serverzijdig API-token.

Omgevingsbestanden, tokens en back-ups blijven buiten Git en buiten de documentroot. Stel alleen benodigde maprechten in voor de web- en CLI-gebruiker; geef niet de hele applicatie algemene schrijfrechten.

## Releaseproces

De [lokale pakketbouwer](staging-en-releases.md) levert inmiddels een ZIP met productiedependencies, assets en controlesommen. De serverinstallatie en koppeling van opslag moeten nog op staging worden getest. Een handmatig te starten GitHub Actions-deployment blijft een vervolgstap; er is nog geen deploymentworkflow.

1. Controleer de gekozen commit, geslaagde checks en eventuele databasemigraties.
2. Bouw met de vastgelegde PHP- en Node-versies en lockbestanden. Gebruik `composer install` en `npm ci`; bouw assets met het werkelijk aanwezige buildscript.
3. Maak een releasepakket zonder `.env`, `.git`, lokale data, testsnapshots met persoonsgegevens of uploads.
4. Upload via SSH/SFTP naar een nieuwe releasemap. Controleer de SSH-hostsleutel via een betrouwbare bron; schakel hostverificatie niet uit.
5. Koppel serverconfiguratie en blijvende opslag. Controleer platformvereisten met `composer check-platform-reqs --no-dev` op het doelplatform.
6. Maak vóór databasemigraties een herstelbare databaseback-up en verifieer dat de vorige release beschikbaar is.
7. Voer alleen beoordeelde migrations uit. Gebruik waar nodig een kort onderhoudsvenster. Cache configuratie en views vanuit de productieomgeving, niet met CI-configuratie.
8. Zet de `current`-link om naar de nieuwe release en beëindig eventueel onderhoud. Voorkom dat twee deploymenttaken tegelijk draaien.
9. Controleer homepage, cv, projectpagina, beheerlogin, private mediatoegang, publieke afbeelding, sitemap en foutgedrag. Controleer de scheduler zodra die gebruikt wordt.
10. Leg commit, releasetijdstip en resultaat vast. Bewaar een beperkt aantal vorige releases en ruim later gecontroleerd op.

Node.js draait voor dit ontwerp alleen tijdens ontwikkeling en build. De productiewebserver levert de gebouwde assets; een blijvende Node-server is niet nodig. Als Composer-installatie op de host beperkt is, kan CI `vendor/` meeleveren zolang het pakket tegen de werkelijke PHP-versie en extensies is gebouwd en op de host wordt gecontroleerd.

Gebruik migraties die tijdens de overgang met de vorige én nieuwe applicatieversie werken. Een brekende schemawijziging krijgt een afzonderlijk herstel- en onderhoudsplan. Een codeversie terugzetten maakt een gewijzigde database niet automatisch ongedaan.

## Geplande taken

Voor de statistiekenuitbreiding roept DirectAdmin-cron de scheduler aan. [Vimexx benadrukt het juiste absolute PHP-pad voor cron](https://www.vimexx.nl/help/hoe-maak-ik-een-cronjob-aan). Controleer welke interval daadwerkelijk is toegestaan.

Sjabloon, nog niet direct uitvoerbaar:

```cron
* * * * * /ABSOLUUT/PAD/NAAR/PHP /home/ACCOUNT/apps/drokers/current/artisan schedule:run >> /home/ACCOUNT/apps/drokers/shared/storage/logs/scheduler.log 2>&1
```

Wanneer slechts een interval van vijf minuten mogelijk is, wordt de scheduler daarop ingericht. Plan de zesuurlijkse taken op tijdstippen die die interval daadwerkelijk raakt. Voorkom overlap in de applicatie en controleer een heartbeat/laatste-succestijd. Een cronregel opslaan is op zichzelf geen bewijs dat de taak werkt.

## Back-up en herstel

Voorgestelde basis: dagelijks een databaseback-up en back-up van uploads, dertig dagen bewaren, plus een extra back-up vóór migrations. Bewaar ten minste één versleutelde kopie buiten hetzelfde Vimexx-account. Providerback-ups zijn aanvullend; hun werkelijke dekking en bewaartermijn worden gecontroleerd.

Bewaar de applicatiesleutel en benodigde configuratie afzonderlijk en beveiligd. Herstel van versleutelde gegevens vereist dezelfde sleutel. Test vóór de eerste livegang het terugzetten van database en media in een afgeschermde omgeving.

Streefwaarden: maximaal 24 uur verlies aan inhoud bij een incident en herstel binnen één werkdag, afhankelijk van beschikbare toegang en back-ups. Dit zijn projectdoelen, geen toegezegde SLA.

Een mislukte release vraagt eerst een beoordeling van de databasewijzigingen. Zonder incompatibele wijziging kan de code naar de vorige release terug. Bij een brekende wijziging volgt het specifieke herstelplan; draai niet automatisch destructieve database-rollbacks op productie.
