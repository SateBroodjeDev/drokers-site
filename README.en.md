# DR Portfolio

My personal website and online résumé, with a content management portal. A place for my experience, interests and projects.

🌐 **[domenicrokers.nl](https://domenicrokers.nl)** · 🇳🇱 **[Nederlands](README.md)**

## What's included?

- 📄 **Online résumé** — experience, education and skills, with expandable details and a print view.
- 🚀 **Projects** — including GlobeTrotr and DutchUnitedRacing, with images, technologies and the story behind each project.
- 📊 **Statistics** — custom figures and a GitHub integration for public repositories and stars.
- ✉️ **Contact form** — receive and manage messages in a private inbox.
- 🌗 **Light and dark** — visitors choose their preferred appearance.

I manage copy, photos, company logos, projects and colours through `/admin`. The portal requires two-factor authentication.

## Built with

**Laravel 13 · Filament 5 · Livewire 4 · Blade · Tailwind CSS · Vite**

PHP 8.3 or later, SQLite for local development and MySQL/MariaDB on hosting. The website itself is in Dutch.

## Run locally

On Windows, install Git and Node.js 24. From the project directory, run:

```powershell
.\scripts\setup.ps1
.\scripts\php.ps1 artisan app:create-admin
.\scripts\php.ps1 artisan serve --host=127.0.0.1
```

Visit `http://127.0.0.1:8000`. Setup installs local tools and dependencies and builds the website. Run it only in a local environment. See the [development guide](docs/ontwikkelen.md) for details.

## Good to know

Contact messages arrive in the portal; email notifications are not configured. Automatic GitHub updates and old-message cleanup use cron. Commit counts, language breakdowns and a real GitHub contribution calendar are not integrated yet.

## Documentation

The detailed guides are currently in Dutch.

- [Managing the website](docs/beheerportaal.md)
- [Development and testing](docs/ontwikkelen.md)
- [Releases and hosting](docs/staging-en-releases.md)
- [Statistics](docs/statistieken.md)
- [Roadmap](roadmap.md)
- [Safe publishing](docs/veilig-publiceren.md)

## Privacy and rights

Configuration, passwords, uploads and databases stay outside Git. Selected public résumé details are included as starter content in the source code.

This is a personal project. Public visibility does not grant a general reuse licence. Personal content, company logos and other trademarks belong to their respective rights holders; bundled font licences are included with the files.
