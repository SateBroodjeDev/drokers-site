# Projectafspraken

## Context

Dit is het persoonlijke portfolio en beheerportaal voor domenicrokers.nl. De inhoudelijke afspraken staan in README.md, roadmap.md en docs/. Houd documentatie en werkelijke implementatie in overeenstemming. Maak onderscheid tussen lokale validatie, een geconfigureerde CI-workflow en een geslaagde hostingdeployment.

## Techniek

- Laravel 13, Filament 5, Livewire 4 en Blade.
- Dependencies vastleggen in composer.lock en package-lock.json.
- Composer richt dependencies op PHP 8.3.0; de portable lokale runtime is PHP 8.4.
- Lokaal SQLite; MySQL/MariaDB als hostingdoel. CI is voorbereid voor SQLite en MySQL.
- Volg Laravel- en Filament-conventies. Installeer extra tools of abstractielagen alleen voor een concreet projectdoel.

## Windows-commando's

PHP en Composer staan projectgebonden in de genegeerde map .tools/. Een globale installatie is niet nodig.

```powershell
.\scripts\setup.ps1
.\scripts\php.ps1 artisan serve --host=127.0.0.1
.\scripts\php.ps1 artisan test --no-ansi
.\scripts\composer.ps1 validate --strict --no-check-publish
.\scripts\composer.ps1 check-platform-reqs
npm.cmd run build
```

Lees docs/ontwikkelen.md voor installatie, het eerste beheeraccount en herstel. Hergebruik een bestaande APP_KEY en bestaande lokale gegevens. Start geen tweede dependency-installatie zolang een eerdere installatie dezelfde inputs gebruikt.

## Inhoud en toegang

- Verzin geen werkervaring, diploma's, technieken, cijfers of projectresultaten.
- Het eerder aangeleverde cv is leidend bij verschillen met `Profile.pdf`. Gebruik de LinkedIn-export alleen voor aanvullingen; behoud de cv-functies en perioden. De bronkeuze is bevestigd door de eigenaar en staat in docs/cv-verwerking.md.
- Houd persoonlijke ontwikkeldata, uploads, .env, sleutels en tokens buiten Git.
- Maak geen standaardbeheeraccount of bekend productiewachtwoord aan. Gebruik app:create-admin voor het eerste account.
- Behoud expliciete controle op is_admin en is_active en verplichte MFA.
- Gebruik overal dezelfde publicatieregels, ook in sitemap, previews, media en statistieken. De publieke site leest via PortfolioContent uit de CMS-tabellen; Support/ResumeContent en PortfolioDesign zijn alleen startbronnen voor de expliciete import.
- Houd de donkerblauwe/oranje standaardrichting en de beheerbare kleurenschema’s aan. Persoonlijke teksten, kleuren en bedrijfslogo’s horen via de bestaande resources instelbaar te blijven.
- Bewaar afbeeldingen op de private disk; lever ze uitsluitend via de gecontroleerde media- en previewroutes. Plaats geen portret of cv-PDF rechtstreeks onder public/.

## Verificatie

Voer tests uit die relevant zijn voor de wijziging en controleer de frontendbuild bij assetwijzigingen. Tests mogen geen live statistiek-API nodig hebben. Gebruik geïsoleerde data en tijdelijke opslag. Vermeld checks die nog niet op Vimexx of GitHub zijn uitgevoerd als open punt.
