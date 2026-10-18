# DR Portfolio

Mijn persoonlijke website en online cv, met een eigen beheerportaal. Hier komen mijn werkervaring, interesses en projecten samen.

🌐 **[domenicrokers.nl](https://domenicrokers.nl)** · 🇬🇧 **[English](README.en.md)**

## Wat zit erin?

- 📄 **Online cv** — werkervaring, opleidingen en vaardigheden, met uitklapbare details en een printversie.
- 🚀 **Projecten** — ruimte voor onder andere GlobeTrotr en DutchUnitedRacing, met beelden, technieken en het verhaal erachter.
- 📊 **Statistieken** — eigen cijfers en een GitHub-koppeling voor openbare repositories en sterren.
- ✉️ **Contactformulier** — berichten ontvangen en beheren in een privé-inbox.
- 🌗 **Licht en donker** — bezoekers kiezen zelf hun weergave.

Teksten, foto's, bedrijfslogo's, projecten en kleuren pas ik aan via `/admin`. Het portaal is beveiligd met tweefactorauthenticatie.

## Gebouwd met

**Laravel 13 · Filament 5 · Livewire 4 · Blade · Tailwind CSS · Vite**

PHP 8.3 of hoger, SQLite voor lokale ontwikkeling en MySQL/MariaDB op hosting. De website is Nederlandstalig.

## Lokaal starten

Op Windows heb je Git en Node.js 24 nodig. Voer vanuit de projectmap uit:

```powershell
.\scripts\setup.ps1
.\scripts\php.ps1 artisan app:create-admin
.\scripts\php.ps1 artisan serve --host=127.0.0.1
```

Open `http://127.0.0.1:8000`. De setup installeert de lokale hulpmiddelen en dependencies en bouwt de website. Gebruik deze setup alleen lokaal. Meer uitleg staat in de [ontwikkelhandleiding](docs/ontwikkelen.md).

## Handig om te weten

Contactberichten staan in het portaal; er worden geen e-mailmeldingen verstuurd. Automatisch GitHub-cijfers ophalen en oude berichten opruimen werkt via cron. Commits, taalverdeling en een echte GitHub-bijdragekalender zijn nog niet gekoppeld.

## Documentatie

- [Website beheren](docs/beheerportaal.md)
- [Ontwikkelen en testen](docs/ontwikkelen.md)
- [Releases en hosting](docs/staging-en-releases.md)
- [Statistieken](docs/statistieken.md)
- [Roadmap](roadmap.md)
- [Veilig publiceren](docs/veilig-publiceren.md)

## Privacy en rechten

Configuratie, wachtwoorden, uploads en databases blijven buiten Git. De broncode bevat wel de geselecteerde openbare cv-gegevens als startinhoud.

Dit is een persoonlijk project. De openbare repository geeft geen algemene hergebruiklicentie. Persoonlijke inhoud, bedrijfslogo's en andere merken blijven van hun rechthebbenden; meegeleverde fontlicenties staan bij de bestanden.
