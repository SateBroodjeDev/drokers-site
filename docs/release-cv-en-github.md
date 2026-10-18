# Release: persoonlijk cv, contact en GitHub

[Terug naar hosting](staging-en-releases.md)

Dit is de nieuwe release `20261017T220220Z`, inclusief logo, profielkaart, contactpagina, licht/donker en GitHub-repositorycijfers. Er is geen aparte werkgeverssectie. Het eerdere ZIP `20261017T213959Z` bevat niet alle wijzigingen. De release-ID is een UTC-tijdstip; in Nederland is deze release op 18 oktober 2026 gebouwd.

## Lokale controles

Het gecontroleerde pakket staat in `.work/releases/drokers-20261017T220220Z.zip` (22.260.256 bytes). Alle 14.581 bestanden zijn tegen het pakketmanifest gecontroleerd. SHA-256: `e7758c9c18eaf00217a11ac2b2f9543423d863e9a3c34028e0ed856890a925b6`. Het pakket bevat 113 productiedependencies en geen lokale `.env`, database of uploads.

De volledige suite slaagt met 49 tests en 436 assertions. De frontendbuild slaagt. De eerste echte GitHub-meting voor het bevestigde profiel SateBroodjeDev is lokaal geslaagd. De webpagina's lezen de opgeslagen meting, zonder een API-verzoek per bezoek.

Er is één aanvullende migration: `2026_10_19_000001_create_github_snapshots_table`. Deze voegt uitsluitend de nieuwe snapshot-tabel toe. Bestaande migraties en Composer-dependencies zijn ongewijzigd. Een schema- of dependencywijziging buiten deze afgebakende update wordt door de hulp geweigerd.

## Uitvoeringsvolgorde

Upload het ZIP met `.zip.sha256` en de **actuele** `scripts/update-hosting.sh` via SFTP/SCP naar de private home-map. Voer daarna de volgende stappen via SSH uit. Stop bij een fout en stuur alleen de foutmelding, geen `.env` of database-export.

Vervang `HOSTING_USER` en `HOSTING_HOST` door je private SSH-gegevens; deze worden niet in de repository bewaard. Pas zo nodig de SSH-poort aan. Voer vanuit de projectmap op je eigen computer in PowerShell uit:

```powershell
scp -P 7685 .work/releases/drokers-20261017T220220Z.zip .work/releases/drokers-20261017T220220Z.zip.sha256 scripts/update-hosting.sh HOSTING_USER@HOSTING_HOST:
ssh -p 7685 HOSTING_USER@HOSTING_HOST
```

Voer daarna in de SSH-sessie uit:

```bash
bash ~/update-hosting.sh prepare 20261017T220220Z
```

Wacht op `VOORBEREID`. De hulp vergelijkt de checksum, gebruikt een nieuwe releasemap en koppelt de bestaande configuratie en opslag. De huidige website blijft actief. Er wordt geen applicatiesleutel gegenereerd, beheeraccount aangemaakt of portfolio-inhoud opnieuw geïmporteerd. De nieuwe release gebruikt de werkelijke actieve release als vertrekpunt; het maakt niet uit of de vorige ontwerpupdate al is geactiveerd.

```bash
bash ~/update-hosting.sh backup 20261017T220220Z
```

Voer bij de verborgen prompt het **portfoliodatabasewachtwoord** in. De hulp leest databasenaam en gebruikersnaam uit de gekoppelde private Laravel-configuratie en accepteert uitsluitend een lokale MySQL-verbinding. Controleer vooraf dat deze configuratie bij de bedoelde portfoliodatabase hoort. De SQL-export wordt buiten de documentroot onder `apps/drokers/backups/20261017T220220Z.sql` opgeslagen, met private rechten en checksum. De definitieve bestandsnaam wordt pas na een geslaagde, niet-lege export gebruikt. Download deze export ook buiten het hostingaccount. Een gemaakte export is nog geen geslaagde hersteltest.

```bash
bash ~/update-hosting.sh migrate 20261017T220220Z
```

Dit controleert de back-up en voert uitsluitend de genoemde GitHub-migration uit, met behoud van bestaande tabellen. Wacht op `MIGRATIE GEREED`. Bij een fout blijft de oude code actief; voer geen generiek `migrate:rollback` uit.

Stel daarna in de nieuwe release het bevestigde profiel in:

```bash
cd ~/apps/drokers/releases/20261017T220220Z
/opt/alt/php83/usr/bin/php artisan app:configure-github SateBroodjeDev --statistics
/opt/alt/php83/usr/bin/php artisan app:sync-github
```

De configuratiestap wijzigt uitsluitend de GitHub-instellingen van het bestaande profiel. Een API-fout verhindert niet dat de overige website werkt: zonder geslaagde meting verschijnen geen verzonnen cijfers. Een eventuele `GITHUB_TOKEN` hoort alleen in de bestaande private serverconfiguratie; voeg hem niet aan een chat of releasepakket toe. Bij wijziging van serverconfiguratie moet de configuratiecache in de nieuwe release opnieuw worden gebouwd.

```bash
bash ~/update-hosting.sh activate 20261017T220220Z
```

Na `ACTIEF` controleer je HTTPS-home, cv, contact, GitHub-link, statistieken, licht/donker en beheerlogin met MFA. Controleer ook een bestaande upload en een profielbewerking. Beoordeel Varnish/Cloudflare bij oude weergave; beheer en private media mogen niet publiek gecachet worden. De nieuwe afbeelding uit de lokale omgeving wordt niet automatisch een upload op de server.

## Terugzetten

```bash
bash ~/update-hosting.sh rollback 20261017T220220Z
```

Hiermee wordt alleen de webrootkoppeling naar de eerdere code teruggezet. De database en uploads blijven behouden; de aanvullende GitHub-tabel blijft staan. Verwijder oude releasemappen niet: configuratie en opslag kunnen daar nog staan. Maak een afzonderlijk herstelplan als databaseherstel nodig is.

## Periodieke synchronisatie

De Laravel-planning roept de synchronisatie iedere zes uur aan. Pas na een succesvolle handmatige test kan een hostingcron worden ingesteld voor het werkelijk gekozen releasepad. Er is nog geen geteste `current`-link, dus neem geen cronpad uit een generiek voorbeeld over. Een volgende release moet ook het cronpad behouden of aanpassen. Een opgeslagen cronregel is nog geen geslaagde schedulercontrole.

Deze release is lokaal voorbereid. De hulp is op Bash-syntaxis gecontroleerd. Back-up, migratie, activering, herstel en cron moeten nog op Vimexx worden uitgevoerd en gecontroleerd.

## Uitgevoerde release

De eigenaar heeft op 18 oktober 2026 gemeld dat deze release is gelukt. Dit is een bevestiging door de eigenaar, geen onafhankelijke HTTP-, herstel- of croncontrole. De repository bevat een veralgemeniseerde versie van de updatehulp; deze is na de uitgevoerde release aangepast om hostingaccountgegevens buiten de publieke broncode te houden.
