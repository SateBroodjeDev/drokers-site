# 👋 Domenic Rokers — persoonlijk portfolio & online cv

Een persoonlijke website die verder gaat dan een cv: werkervaring, opleidingen, projecten, interesses en developmentstatistieken, met een eigen beheerportaal.

🌐 **[Bekijk de website](https://domenicrokers.nl)** · 🇬🇧 **[English documentation](README.en.md)** · 📚 **[Ontwikkelhandleiding](docs/ontwikkelen.md)**

> **Status · 18 oktober 2026:** de eigenaar heeft de release met de vernieuwde vormgeving en GitHub-koppeling als geslaagd bevestigd. Lokaal slaagden 56 tests met 493 assertions en de frontendbuild. GitHub Actions is geconfigureerd; een geslaagde CI-run, hersteltest en hostingcron zijn hiermee niet aangetoond.

## ✨ Wat kan de website?

| Onderdeel | Mogelijkheden |
| --- | --- |
| 👤 Persoonlijk profiel | Introductie, portret, interesses en een eigen visuele identiteit |
| 📄 Uitgebreid cv | Werkervaring, vrijwilligerswerk, opleidingen, certificaten, talen en vaardigheden |
| 🖨️ Printbaar cv | Dezelfde gepubliceerde gegevens in een printweergave; als PDF bewaren via de browser |
| 🚀 Projecten & initiatieven | Overzicht en detailpagina’s met bijdrage, technieken, beelden en externe links |
| 📊 Statistieken | Handmatige cijfers met bron en peildatum, interne tellingen en opgeslagen GitHub-metingen |
| 🐙 GitHub | Profielkoppeling, openbare eigen repositories en ontvangen sterren; met bron en meetmoment |
| 🌗 Licht & donker | Beheerbare standaardweergave en een schakelaar die de bezoekerskeuze lokaal onthoudt |
| ✉️ Contact | Formulier met privé-inbox in het portaal en optionele openbare links |
| 🔎 Vindbaarheid | Metadata en sitemap op basis van gepubliceerde inhoud |

De website is een uitgebreid persoonlijk cv voor iedereen die wil weten wie ik ben en wat ik doe. Er is geen aparte werkgeverssectie. De publieke interface is momenteel Nederlands; deze repository heeft documentatie in twee talen.

## 🎛️ Zelf beheren via het portaal

Via `/admin` zijn profielteksten, kleuren, beweging, pagina-instellingen, organisaties en bedrijfslogo’s, cv-onderdelen, projecten, afbeeldingen, contactlinks en statistieken te beheren. Inhoud kan worden geordend, als concept opgeslagen, gepubliceerd of verborgen.

- 🔐 Alleen actieve beheerders krijgen toegang; tweefactorauthenticatie is verplicht.
- 🖼️ Uploads staan op private opslag en worden via gecontroleerde media- en previewroutes geleverd.
- 📝 De publieke site leest gepubliceerde CMS-gegevens; startdata worden alleen expliciet geïmporteerd.
- 🛟 Er zijn interactieve commando’s voor het eerste beheeraccount en account­herstel. Er bestaat geen standaardbeheerder met een bekend wachtwoord.

Lees [Je website beheren](docs/beheerportaal.md) voor de dagelijkse werkwijze.

## 🧰 Techniek

| Laag | Implementatie |
| --- | --- |
| Applicatie | Laravel 13 · PHP 8.3+ |
| Beheer | Filament 5 · Livewire 4 |
| Publieke pagina’s | Blade · Tailwind CSS 4 · Vite · JavaScript |
| Database | Lokaal SQLite; hosting MySQL/MariaDB |
| Hosting | Vimexx-webhosting, DNS via Cloudflare |
| Achtergrondtaken | Korte cron-/CLI-taken; geen permanent draaiende worker vereist |
| Kwaliteit | PHPUnit, frontendbuild en GitHub Actions voor PHP 8.3/8.4 met SQLite/MySQL |

Exacte versies staan in `composer.lock` en `package-lock.json`. Composer richt dependencies op PHP 8.3.0; de lokale Windows-runtime gebruikt PHP 8.4. Node.js 24 staat vastgelegd in `.nvmrc`.

## 🏁 Lokaal starten

Op Windows met Git en Node.js 24, vanuit de projectmap:

```powershell
.\scripts\setup.ps1
.\scripts\php.ps1 artisan app:create-admin
.\scripts\php.ps1 artisan serve --host=127.0.0.1
```

Open `http://127.0.0.1:8000`; het portaal staat op `/admin`. Stel bij de eerste aanmelding een authenticator-app in en bewaar de herstelcodes privé.

De setup plaatst gecontroleerde PHP- en Composer-versies in `.tools/`, installeert dependencies, maakt de lokale database gereed, importeert startinhoud en bouwt assets. Bestaande lokale sleutels en gegevens blijven behouden. **Gebruik deze setup uitsluitend lokaal.** Met een eigen geschikte PHP- en Composer-installatie kan `composer setup` worden gebruikt. Zie [Ontwikkelen](docs/ontwikkelen.md) voor vereisten en updates van een bestaande checkout.

## ✅ Controleren

```powershell
.\scripts\php.ps1 scripts/check-public-repository.php --history
.\scripts\composer.ps1 validate --strict --no-check-publish
.\scripts\composer.ps1 check-platform-reqs
.\scripts\php.ps1 artisan test --no-ansi
npm.cmd run build
```

De repositorycontrole bekijkt Git-kandidaten, de index en optioneel herkenbare geheimen in de geschiedenis, zonder gevonden waarden af te drukken. Dit is een gerichte controle, geen garantie dat ieder mogelijk geheim wordt herkend. Applicatietests gebruiken geïsoleerde data en vereisen geen live statistiek-API.

## 📦 Releasen

```powershell
.\scripts\build-release.ps1
```

Dit maakt onder `.work/releases/` een productie-ZIP met dependencies, gebouwde assets, een bestandsmanifest en SHA-256-controlesom. Lokale configuratie, databases en uploads horen niet in het pakket. De bestaande productieconfiguratie, applicatiesleutel en opslag worden bij een update hergebruikt.

De deployment is handmatig via SSH. De updatehulp is bedoeld voor de gedocumenteerde releasemapstructuur en de specifiek beoordeelde GitHub-migratie; het is geen algemene deployservice. Vervang placeholders in voorbeelden door je eigen private hostinggegevens. Zie [Releases](docs/staging-en-releases.md) en [de uitgevoerde cv/GitHub-release](docs/release-cv-en-github.md).

## 🧭 Grenzen en vervolgstappen

- GitHub-commits, een echte bijdragekalender en taalverdeling zijn nog niet gekoppeld. Een eventuele demokalender wordt als demo aangeduid.
- De scheduler bevat een synchronisatie iedere zes uur; uitvoering vereist een correct ingestelde en gecontroleerde hostingcron.
- GitHub-fouten leveren geen verzonnen cijfers op; een eerdere meting kan behouden blijven.
- Een blog, bezoekersaccounts en automatische PDF-generatie op de server zijn niet gebouwd.
- Compact cv en contactformulier met portaalinbox zijn lokaal toegevoegd na de bevestigde release. Hosting vereist eerst de nieuwe contactberichten-migration. E-mailnotificaties zijn niet ingericht; antwoorden kan via je eigen e-mailprogramma.
- De applicatie gaat uit van één eigenaar, zonder openbare registratie.

## 📚 Meer lezen

| Document | Onderwerp |
| --- | --- |
| [Beheerportaal](docs/beheerportaal.md) | Inhoud, kleuren, uploads en publicatie |
| [Technisch ontwerp](docs/technisch-ontwerp.md) | Architectuur, authenticatie en opslag |
| [Statistieken](docs/statistieken.md) | Definities, bronnen en foutafhandeling |
| [Vormgeving](docs/vormgeving-en-content.md) | Visuele richting en inhoud |
| [Projectinhoud](docs/projectinhoud.md) | GlobeTrotr, DutchUnitedRacing en eigen bijdragen |
| [Bedrijfslogo’s](docs/bedrijfslogos.md) | Beeldbronnen en weergave |
| [Roadmap](roadmap.md) | Bouwfasen en open punten |
| [Persoonlijke GitHub-README](docs/github-profiel.md) | Plaatsingsinstructies voor de afzonderlijk bewaarde profieltekst |
| [Compact cv & contactformulier](docs/compact-cv-en-contact.md) | Nieuwe lokale wijziging, inbox en benodigde migration |
| [Veilig publiceren / Safe publishing](docs/veilig-publiceren.md) | Repositorycontrole, private bestanden en beperkingen |

## 🔒 Privacy & gebruiksrechten

Wachtwoorden, tokens, `.env`, databases, bron-cv’s, portretten, uploads en back-ups blijven buiten Git. Geselecteerde professionele cv-gegevens staan bewust als startinhoud in de broncode; verbergen op de website verwijdert die gegevens niet uit een publieke repository.

De repository is openbaar ter inzage. Dit geeft geen algemene hergebruiklicentie: `composer.json` vermeldt `proprietary`. Persoonlijke inhoud en merken blijven bij hun rechthebbenden. Fontlicenties en bronnen van bedrijfslogo’s zijn afzonderlijk opgenomen.
