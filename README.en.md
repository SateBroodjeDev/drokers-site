# 👋 Domenic Rokers — personal portfolio & online résumé

A personal website that goes beyond a résumé: experience, education, projects, interests and development statistics, backed by a content management portal.

🌐 **[Visit the website](https://domenicrokers.nl)** · 🇳🇱 **[Nederlandse documentatie](README.md)** · 📚 **[Development guide, Dutch](docs/ontwikkelen.md)**

> **Status · 18 October 2026:** the owner confirmed that the release with the updated design and GitHub integration succeeded. Local validation passed 56 tests with 493 assertions and the frontend build. GitHub Actions is configured; this does not establish a successful CI run, restore test or hosting cron run.

## ✨ Features

| Area | Capabilities |
| --- | --- |
| 👤 Personal profile | Introduction, portrait, interests and a personal visual identity |
| 📄 Extended résumé | Employment, volunteering, education, certificates, languages and skills |
| 🖨️ Print view | The same published résumé data, ready to print or save as PDF in the browser |
| 🚀 Projects & initiatives | Overview and detail pages covering contributions, technologies, images and links |
| 📊 Statistics | Manual figures with sources and dates, internal counts and stored GitHub measurements |
| 🐙 GitHub integration | Profile link, public owned repositories and stars received, with source and measurement time |
| 🌗 Light & dark | Editable default appearance and a visitor toggle with a locally remembered preference |
| ✉️ Contact | Contact form with a private portal inbox and optional public links |
| 🔎 Discoverability | Metadata and a sitemap that follow publication rules |

The site is an extended personal résumé for anyone curious about my background and projects. There is no separate employer section. The public interface currently uses Dutch; the repository documentation is available in Dutch and English.

## 🎛️ Content management

The `/admin` portal manages profile copy, colours, motion, page settings, organisations and company logos, résumé entries, projects, images, contact links and statistics. Content can be ordered, saved as a draft, published or hidden.

- 🔐 Access requires an active administrator account and mandatory two-factor authentication.
- 🖼️ Uploaded images use private storage and controlled media and preview routes.
- 📝 Public pages read published CMS records. Starter content is imported explicitly.
- 🛟 Interactive commands create the first administrator and recover access. There is no default administrator with a known password.

The [portal guide, Dutch](docs/beheerportaal.md) explains everyday editing.

## 🧰 Stack

| Layer | Implementation |
| --- | --- |
| Application | Laravel 13 · PHP 8.3+ |
| Administration | Filament 5 · Livewire 4 |
| Public frontend | Blade · Tailwind CSS 4 · Vite · JavaScript |
| Database | SQLite locally; MySQL/MariaDB on the hosting target |
| Hosting | Vimexx shared hosting; Cloudflare DNS |
| Background work | Short cron/CLI tasks; no permanent worker required |
| Validation | PHPUnit, frontend build and a GitHub Actions matrix for PHP 8.3/8.4 with SQLite/MySQL |

Exact versions are recorded in `composer.lock` and `package-lock.json`. Composer resolves for PHP 8.3.0; the portable Windows runtime uses PHP 8.4. `.nvmrc` selects Node.js 24.

## 🏁 Local development

On Windows, install Git and Node.js 24, then run from the project directory:

```powershell
.\scripts\setup.ps1
.\scripts\php.ps1 artisan app:create-admin
.\scripts\php.ps1 artisan serve --host=127.0.0.1
```

Visit `http://127.0.0.1:8000`; administration is available at `/admin`. Enrol an authenticator app on first login and store recovery codes privately.

Setup installs verified PHP and Composer versions in the ignored `.tools/` directory, installs dependencies, prepares the local database, imports starter content and builds assets. Existing local keys and data are preserved. **Run setup only in a local environment.** With your own compatible PHP and Composer installation, use `composer setup`, followed by `php artisan app:create-admin` and `php artisan serve`. See the [development guide, Dutch](docs/ontwikkelen.md) for details and existing-checkout updates.

## ✅ Validation

```powershell
.\scripts\php.ps1 scripts/check-public-repository.php --history
.\scripts\composer.ps1 validate --strict --no-check-publish
.\scripts\composer.ps1 check-platform-reqs
.\scripts\php.ps1 artisan test --no-ansi
npm.cmd run build
```

The repository check inspects Git candidates, staged versions and optionally history for selected secret patterns, without printing matched values. It cannot detect every possible secret. Application tests use isolated data and do not require a live statistics API. The GitHub workflow also runs the repository check.

## 📦 Releases

```powershell
.\scripts\build-release.ps1
```

The builder creates a production ZIP, file manifest and SHA-256 checksum under `.work/releases/`. It includes production dependencies and compiled assets, excluding local configuration, databases and uploads. Updates reuse the existing production configuration, application key and storage.

Deployment is manual over SSH. The helper targets the documented release directory layout and the specifically reviewed additive GitHub migration; it is not a general deployment service. Replace hosting placeholders with your own private account details. See [release packaging, Dutch](docs/staging-en-releases.md) and the [résumé/GitHub release procedure, Dutch](docs/release-cv-en-github.md).

## 🧭 Current limits

- GitHub commits, a real contribution calendar and language breakdown are not integrated. Any demonstration calendar is labelled as a demo.
- The scheduler defines a six-hour sync interval. Automatic execution requires a configured and verified hosting cron job.
- API failures do not produce fabricated numbers; a previous successful measurement can be retained.
- A blog, visitor accounts and server-generated PDFs are not implemented.
- A compact résumé and contact form with a private inbox were added locally after the confirmed release. Hosting requires the new contact-message migration first. Email notifications are not configured; replies use the administrator’s own email client.
- The application is designed for one owner, without public registration.

## 📚 Documentation

Most detailed project documentation is currently in Dutch.

| Document | Covers |
| --- | --- |
| [Portal guide](docs/beheerportaal.md) | Content, colours, uploads and publication |
| [Technical design](docs/technisch-ontwerp.md) | Architecture, authentication and storage |
| [Statistics](docs/statistieken.md) | Definitions, sources and failure handling |
| [Visual direction](docs/vormgeving-en-content.md) | Design and content |
| [Project content](docs/projectinhoud.md) | GlobeTrotr, DutchUnitedRacing and contributions |
| [Company logos](docs/bedrijfslogos.md) | Asset sources and display |
| [Roadmap](roadmap.md) | Delivery stages and open items |
| [Personal GitHub README](docs/github-profiel.md) | Publishing instructions for the separately maintained profile text |
| [Compact résumé & contact form](docs/compact-cv-en-contact.md) | Local update, private inbox and required migration |
| [Safe publishing](docs/veilig-publiceren.md) | Bilingual publication checklist and limitations |

## 🔒 Privacy & usage rights

Passwords, tokens, `.env` files, databases, original résumé documents, portraits, uploads and backups stay outside Git. Selected professional résumé information is intentionally included as starter content. Hiding a record on the website does not remove its source data from a public repository.

Public visibility does not grant a general reuse licence: `composer.json` declares the project `proprietary`. Personal content and trademarks remain with their respective rights holders. Font licences and company-logo sources are documented separately.
