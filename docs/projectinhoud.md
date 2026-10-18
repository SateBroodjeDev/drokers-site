# Projectinhoud en beelden

[Terug naar de README](../README.md) · [Beheerhandleiding](beheerportaal.md)

## Bevestigde inhoud

De eigenaar heeft de onderstaande gegevens zelf aangeleverd. Deze aanvulling is leidend voor de projectbeschrijvingen en de rol bij DutchUnitedRacing. De eerdere afspraak over cv-perioden blijft gelden. Er zijn geen resultaten, gebruikerscijfers, startdatum van GlobeTrotr of publieke DutchUnitedRacing-link bij verzonnen.

| Project | Eigen rol | Doel | Technieken / platforms |
| --- | --- | --- | --- |
| GlobeTrotr | Oprichter & Full-stack Developer | Reisplanning en -beheer: routes, accommodaties, activiteiten, budgetten en samen plannen | HTML, CSS, JavaScript, Supabase, Leaflet.js en API-integraties |
| DutchUnitedRacing | Oprichter & Race Director | Een professionele online simracecompetitie in een gestructureerde, competitieve omgeving | Assetto Corsa Competizione (ACC), Discord, digitale wedstrijdadministratie en simracingserverbeheer |
| Dit portfolio | Eigen portfolio | Werkervaring, projecten en persoonlijkheid tonen met zelfstandig inhoudsbeheer | Laravel, Filament, Blade en Tailwind; vastgesteld aan de applicatie |

Bij GlobeTrotr staan het zelfstandige concept- en UX/UI-werk, ontwikkeling en database-inrichting. Authenticatie, interactieve kaarten, synchronisatie en Supabase Row Level Security staan als ontwikkelwerk beschreven. Het project blijft **In ontwikkeling** en verwijst naar `https://globetrotr.nl`.

Bij DutchUnitedRacing staan organisatie, dagelijkse aansturing, wedstrijdleiding, reglementen, incidentbeoordeling, evenementenplanning, deelnemerscommunicatie en teamcoördinatie. December 2023 komt uit de eerdere bron. De rol en werkzaamheden zijn ook verwerkt onder **Eigen initiatief** in het online en printbare cv.

Het portfolio beschrijft de feitelijk gebouwde site, met beheerbare inhoud en uitstraling, gecontroleerde media en gedeelde cv-weergaven. Hosting blijft een volgende stap en de GitHub-kalender blijft een demonstratie.

## Projectpagina’s en portaal

Het formulier heeft vier tabbladen:

1. **Het project:** kerngegevens, status, rol, omschrijving, links en techniek-/platformtags.
2. **Het verhaal:** koppen, introductie en herhaalbare hoofdstukken. De publieke pagina heeft een inhoudsopgave met ankers naar de hoofdstukken.
3. **Afbeeldingen:** cover en een versleepbare lijst projectbeelden. De volgorde blijft behouden bij opnieuw openen en opslaan.
4. **Weergave:** illustratie zonder cover, uitlichten en volgorde.

Zoeken gebruikt naam, domein, omschrijving, projectsoort, rol en technieken. Daardoor is DutchUnitedRacing ook op “Discord” te vinden. De techniekfilters gebruiken uitsluitend tags van gepubliceerde projecten en hebben deelbare URL’s.

## Echte beelden

De lokale portfolio-detailpagina heeft drie echte screenshots: home, online cv en projectoverzicht. Ze komen van de werkende lokale website en staan als eigen media privé opgeslagen, buiten Git. Op een nieuwe omgeving zet je een mediaback-up terug of upload je ze opnieuw.

Voor GlobeTrotr en DutchUnitedRacing zijn nog geen echte screenshots of officiële eigen logo’s aangeleverd. De ontwerpillustraties blijven daar de visuele terugval. Nieuwe uploads zijn via het portaal te koppelen.

Een afbeelding heeft een alt-tekst, optioneel bijschrift en interne bronvelden. De galerij toont het bijschrift onder het beeld. Bezoekers kunnen afbeeldingen groot openen, met Vorige/Volgende of de pijltjestoetsen wisselen en met Escape sluiten. Focus keert terug naar de geopende afbeelding. Zonder JavaScript werkt de gewone afbeeldingslink. De viewer gebruikt een native `dialog`, zonder externe galerijbibliotheek.

## Bestaande checkout bijwerken

Nieuwe startimports bevatten de nieuwe beschrijvingen. Voor eerder geïmporteerde startteksten is er een afzonderlijke aanvulling:

```powershell
.\scripts\php.ps1 artisan migrate
.\scripts\php.ps1 artisan app:import-portfolio
.\scripts\php.ps1 artisan app:import-project-details
```

`app:import-project-details` vervangt alleen velden die nog exact gelijk zijn aan de eerdere startteksten. Eigen redactie, afbeeldingen, aangepaste slugs en publicatiestatus blijven behouden. Verwijderde projecten worden niet hersteld. Het commando is herhaalbaar en wordt niet automatisch bij ieder bezoek of iedere inhoudswijziging gestart.

Dagelijkse aanpassingen gaan via het portaal. De import is geen permanente synchronisatie met teksten in de repository.
