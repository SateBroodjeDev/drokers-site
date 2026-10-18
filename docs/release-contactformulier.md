# Release: compact cv en werkend contactformulier

[Beschrijving en controles](compact-cv-en-contact.md) · [Hosting](staging-en-releases.md)

Release-ID: `20261018T110735Z`. Deze update bouwt voort op de door de eigenaar bevestigde GitHub-release `20261017T220220Z`. Bestaande inhoud, accounts, uploads en de applicatiesleutel blijven behouden. De nieuwe tabel is `contact_messages`; bestaande migraties en Composer-dependencies zijn ongewijzigd.

Het pakket is lokaal gebouwd en gecontroleerd: 14.591 manifestbestanden, 113 productiedependencies, 22.270.742 bytes. SHA-256: `75193bd56eefa91ca2b937dc3c4db4f0044f534c448a77edf594ac18e937d757`. Configuratie, database en uploads zijn niet opgenomen. De frontendbuild, 56 tests met 493 assertions en 110 browsercontroles zijn geslaagd. Dit bewijst nog geen geslaagde deployment van deze release op Vimexx.

De website ontvangt berichten rechtstreeks in een privé-inbox in het portaal. Er is geen SMTP-configuratie nodig om berichten te ontvangen. Er worden geen e-mailmeldingen verstuurd; controleer **Contactberichten** in het portaal. De antwoordactie opent je eigen e-mailprogramma.

## 1. Uploaden vanaf je computer

Voer in PowerShell vanuit de projectmap uit. Vervang `HOSTING_USER` en `HOSTING_HOST` door je bestaande SSH-gegevens. Pas de poort aan als jouw account daarvan afwijkt.

```powershell
scp -P 7685 .work/releases/drokers-20261018T110735Z.zip .work/releases/drokers-20261018T110735Z.zip.sha256 scripts/update-hosting.sh HOSTING_USER@HOSTING_HOST:
ssh -p 7685 HOSTING_USER@HOSTING_HOST
```

Upload ook de **nieuwe updatehulp**: het exemplaar van de vorige release accepteert de nieuwe migration niet. Het script blijft buiten de publieke documentroot.

## 2. Voorbereiden en back-up maken

Voer via SSH één stap tegelijk uit. Stop bij fouten; verwijder geen gedeeltelijke releasemappen en voer geen `migrate:fresh` uit.

```bash
bash ~/update-hosting.sh prepare 20261018T110735Z
```

Wacht op **VOORBEREID**. De bestaande website blijft actief.

```bash
bash ~/update-hosting.sh backup 20261018T110735Z
```

Vul bij de verborgen prompt het wachtwoord van je portfoliodatabase in. De hulp leest de databasenaam en gebruiker uit de bestaande private configuratie. Het wachtwoord komt niet in het script of de chat. Wacht op **BACK-UP GEMAAKT** en download een kopie naar je eigen computer, in een apart lokaal PowerShell-venster:

```powershell
scp -P 7685 HOSTING_USER@HOSTING_HOST:apps/drokers/backups/20261018T110735Z.sql "$HOME\Downloads\drokers-backup-20261018T110735Z.sql"
```

## 3. Migreren en activeren

Ga terug naar de SSH-sessie:

```bash
bash ~/update-hosting.sh migrate 20261018T110735Z
```

Wacht op **MIGRATIE GEREED**. Alleen de beoordeelde contact-migration wordt uitgevoerd, na controle van de databaseback-up. Er wordt geen inhoud opnieuw geïmporteerd.

```bash
bash ~/update-hosting.sh activate 20261018T110735Z
```

Na **ACTIEF** staat de nieuwe versie aan. Controleer de cv-pagina, het openklappen van functies, de volledige printweergave en de kleinere footer.

## 4. Een echt testbericht versturen

1. Open `/contact` via HTTPS, vul het formulier in en verstuur een herkenbaar testbericht.
2. Controleer de ontvangstmelding. Een melding betekent dat de applicatie het bericht heeft opgeslagen, niet dat een e-mail is verzonden.
3. Log met MFA in op `/admin` en open **Contactberichten**. Het bericht moet daar staan.
4. Controleer **Antwoorden via e-mail**, markeer het bericht als afgehandeld en verwijder het testbericht.
5. Verwijder eventuele Cloudflare-/Varnish-caches voor oude pagina's. `/contact`, `/admin` en previews moeten buiten publieke paginacaching blijven. Gebruik geen algemene Cache Everything-regel voor het contactformulier.

## 5. Automatische taken

Een werkend formulier vereist geen cron, maar de zesuurlijkse GitHub-synchronisatie en dagelijkse opschoning van berichten wel. Bewerk een bestaande Laravel-cron in plaats van een tweede identieke taak toe te voegen. Gebruik in DirectAdmin de planning **iedere minuut** met als commando, na vervanging van `HOSTING_USER`:

```bash
/opt/alt/php83/usr/bin/php /home/HOSTING_USER/apps/drokers/releases/20261018T110735Z/artisan schedule:run >> /home/HOSTING_USER/apps/drokers/releases/20261018T110735Z/storage/logs/scheduler.log 2>&1
```

De scheduler draait alleen kort; er is geen permanente worker nodig. Controleer de daadwerkelijke cronuitvoering op hosting. Een opgeslagen cronregel is nog geen geslaagde uitvoering. Bij een volgende release moet dit concrete releasepad worden bijgewerkt. Totdat cron werkt, verwijder je oude berichten handmatig of voer je na controle van het juiste releasepad het prune-commando uit.

## Terugzetten

```bash
bash ~/update-hosting.sh rollback 20261018T110735Z
```

Dit herstelt de eerdere webrootkoppeling. De contacttabel en ontvangen berichten worden behouden. Verwijder geen oude releases: private configuratie en opslag kunnen daar nog staan. Bij codeherstel moet ook een gewijzigd cronpad worden gecontroleerd.

De vernieuwde hulp is lokaal op Bash-syntaxis gecontroleerd. Back-up, migratie, activering, herstel en scheduler van deze release moeten nog op Vimexx worden uitgevoerd en gecontroleerd.
