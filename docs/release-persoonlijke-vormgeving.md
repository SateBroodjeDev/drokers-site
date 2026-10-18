# Release: persoonlijke vormgeving en licht/donker

**Historisch document.** De meest recente procedure staat bij [de contactrelease](release-contactformulier.md). De huidige updatehulp hoort bij die nieuwe release.

**Let op: dit document beschrijft het eerdere pakket `20261017T213959Z`.** De huidige werkmap bevat daarna logo-, profielkaart- en contactwijzigingen plus een GitHub-koppeling met nieuwe migration `2026_10_19_000001_create_github_snapshots_table`. Die staan niet in dit oude ZIP. Gebruik voor alle huidige wijzigingen de nieuwe [releaseprocedure voor cv, contact en GitHub](release-cv-en-github.md), inclusief de bijgewerkte hulp met back-up- en migratiestappen.

[Terug naar de releasehandleiding](staging-en-releases.md)

Deze update bevat de donkere persoonlijke vormgeving, interessekaarten, een compact cv, nieuwe portaalinstellingen en de bezoekersschakelaar voor licht/donker. De productieconfiguratie, sleutel, accounts, database en uploads blijven behouden. Er zijn geen nieuwe migraties of gewijzigde Composer-dependencies. De lokale volledige suite slaagt met 41 tests en 369 assertions; de frontendbuild slaagt. De browsercontroles staan in [Ontwerpoplevering](ontwerpoplevering.md).

Het gebouwde pakket is `.work/releases/drokers-20261017T213959Z.zip` (22.249.260 bytes), met checksum in het naastliggende `.zip.sha256`-bestand. SHA-256: `b44f96ed084047cab98850f8f2fd243935c3bb5fcaf1c950133175efdd00e197`. Alle 14.573 manifestbestanden zijn na het bouwen gecontroleerd, evenals de afwezigheid van lokale configuratie/database en ontwikkeldependencies. Het pakket bevat 113 productiepakketten; de lokale hostingcontrole slaagt.

## Voorbereiden op de server

De huidige installatie staat volgens de eigenaar via `public_html` aan een release gekoppeld. `scripts/update-hosting.sh` is een afzonderlijke hulp voor dit Vimexx-account; upload deze naast het nieuwe ZIP en de checksum naar de private home-map. Voer deze niet uit op de ontwikkelmachine.

```bash
bash ~/update-hosting.sh prepare 20261017T213959Z
```

De hulp controleert paden, de actieve Laravel-symlink, checksum, ongewijzigde dependencies en migraties. Hij pakt uit naar een nieuwe releasemap en koppelt de bestaande private `.env` en opslag. De lege opslag uit het ZIP blijft bewaard als `storage.release-empty`. Er wordt geen sleutel gegenereerd, geen account aangemaakt, geen inhoud geïmporteerd en geen migratie uitgevoerd. De hulp leest configuratie voor Laravel maar toont geen wachtwoorden.

Oude assets met een hash in de bestandsnaam worden aanvullend bewaard voor reeds geopende pagina's. Vervolgens lopen de hostingcontrole, Laravel-versiecontrole, migratiestatus en configuratiecache. Bij succes verschijnt `VOORBEREID`. Het domein wijst dan nog steeds naar de bestaande versie. Bij een fout stopt de voorbereiding; stuur de foutmelding voordat je opnieuw probeert. Een gedeeltelijk uitgepakte map wordt bewust niet overschreven of verwijderd.

## Activeren en controleren

Voer alleen na `VOORBEREID` uit:

```bash
bash ~/update-hosting.sh activate 20261017T213959Z
```

Dit vervangt de webrootkoppeling via een tijdelijke symlink en rename, met controle dat de actieve versie niet tussentijds is gewijzigd. Controleer daarna home, cv, projecten, licht/donker, de portaalbewerking, MFA en een bestaande upload. Controleer ook HTTPS en eventuele Varnish-/Cloudflare-caches; geen algemene HTML-cache voor beheer, previews of persoonlijke sessies.

Je bestaande portret, contactlinks en andere CMS-bewerkingen worden niet uit je lokale ontwikkelomgeving overgenomen. Een wijziging aan het uiterlijk kan direct zichtbaar zijn, maar nieuwe persoonlijke media moeten via het portaal of een afzonderlijke beveiligde overdracht worden toegevoegd.

## Terugzetten

Bij problemen na activeren:

```bash
bash ~/update-hosting.sh rollback 20261017T213959Z
```

De hulp controleert dat precies deze update actief is en koppelt de eerdere release terug. Dit is een terugzetactie voor de applicatiecode. Beide versies blijven dezelfde configuratie, database en uploads gebruiken. Verwijder de oorspronkelijke release niet: deze eerste update hergebruikt de daar aanwezige configuratie en opslag. Gedeelde opslag naar een zelfstandige `shared/`-map verplaatsen is een latere, afzonderlijk te testen onderhoudsstap.

De hulp is lokaal op Bash-syntaxis gecontroleerd. De uitvoering op Vimexx en de uiteindelijke HTTP-controle moeten nog door de eigenaar worden uitgevoerd; een lokaal gereed pakket is geen bewijs van een afgeronde deployment.
