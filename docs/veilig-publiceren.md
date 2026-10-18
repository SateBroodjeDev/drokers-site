# 🔒 Veilig publiceren / Safe publishing

[Nederlands](../README.md) · [English](../README.en.md)

## 🇳🇱 Wat hoort in Git?

Broncode, migraties, lockbestanden, tests, documentatie, lege configuratievoorbeelden en bewust geselecteerde openbare startinhoud. Bedrijfslogo’s hebben gedocumenteerde bronnen; openbaar maken verleent geen rechten op die merken. De oorspronkelijke cv-documenten, tekstextracties en het portret horen er niet in.

De `.gitignore` sluit configuratie, private sleutels, databases, exports, archieven, PDF’s, uploads, logs, caches, dependencies en lokale hulpmappen uit. Uitsluiten verwijdert geen bestanden die al gevolgd worden. Gebruik geen `git add -f` voor deze bestanden.

Voer vóór een commit uit:

```powershell
.\scripts\php.ps1 scripts/check-public-repository.php --history
git status --short
git diff --cached --stat
```

Bekijk daarna de daadwerkelijke staged wijzigingen privé met `git diff --cached`. Controleer ook nieuwe afbeeldingen op zichtbare persoonsgegevens. De automatische controle leest geen genegeerde `.env`, print geen gevonden geheimen en controleert wel reeds gevolgde bestanden, de index en optioneel tokenpatronen in de bereikbare Git-geschiedenis. CI voert de bestands- en indexcontrole uit. Deze herkenning dekt geen willekeurige wachtwoorden, alle providers of persoonsgegevens in afbeeldingen.

De repository bevat bewust naam, werkervaring en andere geselecteerde professionele startgegevens in `ResumeContent` en `PortfolioDesign`. CMS-publicatieregels gelden voor de website; de broncode van een openbare repository is rechtstreeks leesbaar. Originele documenten, privécontactgegevens, actuele CMS-inhoud en lokale meetresultaten worden niet vanuit de database geëxporteerd naar Git.

De controle op 18 oktober 2026 betreft deze lokale checkout en de daarin bereikbare Git-geschiedenis. Een externe GitHub-repository, forks, eerdere externe commits, Actions-logs en GitHub-instellingen zijn hiermee niet gecontroleerd. Een latere push verandert de reikwijdte van die controle niet.

Lokale verificatie: 195 Git-kandidaatbestanden zonder melding, vier controles op het blokkeren van een testtoken, een afwijkende staged versie en een geforceerd toegevoegd privébestand, vier releasepakkettests met 48 assertions, PHP-/Bash-syntaxiscontrole en 101 geldige lokale documentatielinks. Voor de negatieve controles zijn uitsluitend synthetische waarden, een afzonderlijke tijdelijke Git-index en lokale testobjecten gebruikt.

Hostinggebruikersnamen en accountgebonden databasegegevens zijn uit de publieke handleiding en updatehulp gehaald. De hulp leest nu de niet-geheime databasevelden uit de bestaande private Laravel-configuratie; het wachtwoord wordt alleen interactief aan `mysqldump` doorgegeven. De aangepaste hulp is lokaal gecontroleerd, maar na deze veralgemenisering nog niet opnieuw op Vimexx uitgevoerd. Gebruik voor volgende migraties een afzonderlijk beoordeelde releaseprocedure.

Als een echt geheim toch is gepubliceerd: trek het eerst in of roteer het bij de provider. Alleen het bestand verwijderen of `.gitignore` aanpassen maakt het geheim niet ongeldig en verwijdert het niet uit de geschiedenis. Bespreek een kwetsbaarheid via een privécontactroute van de eigenaar; plaats geen tokens, exports of herstelcodes in een openbare issue.

## 🇬🇧 What belongs in Git?

Source code, migrations, dependency locks, tests, documentation, empty environment templates and deliberately selected public starter content. Company-logo sources are documented; public availability does not grant trademark rights. Original résumé files, extracted documents and the portrait do not belong in the repository.

The ignore rules exclude private configuration, keys, databases, exports, archives, PDFs, uploads, logs, caches, dependencies and local tooling. Ignore rules do not untrack existing files. Do not force-add private files.

Run the check above before committing, inspect the staged diff privately, and review new images for visible personal data. The automated check examines Git candidates and staged contents; `--history` also checks reachable history for selected secret patterns. It does not read ignored environment files or print matched secrets. CI checks candidate files and the index. This is a focused safeguard, not a complete secret scanner or image-privacy review.

Selected professional résumé details are intentionally present in starter source classes. Website publication settings do not hide public repository source. Original documents, private contact details, current CMS records and local measurements are not exported from the database into Git.

The 18 October 2026 review covers this local checkout and its reachable history. It does not establish the state of a remote GitHub repository, forks, external history, Actions logs or repository settings. A later push does not extend the scope of that review.

Local validation: 195 Git candidate files passed; four guard checks covered synthetic token detection, a staged secret with a clean working copy, a force-added private file and clean input. Four release-package tests passed with 48 assertions. PHP/Bash syntax and 101 local documentation links were checked. Negative checks used an isolated temporary Git index and local test objects.

Hosting-account identifiers were removed from public instructions and the deployment helper. The helper now reads non-secret database connection fields from private Laravel configuration; the password is entered interactively for `mysqldump`. The revised helper was validated locally but has not been rerun on Vimexx since this change. Future schema changes need their own reviewed release procedure.

If a real secret is published, revoke or rotate it first. Deleting a file or adding an ignore rule does not invalidate the secret or remove it from history. Report vulnerabilities through a private contact route to the owner; never attach secrets, database exports or recovery codes to a public issue.
