# Productvisie

[Terug naar de README](../README.md)

## Doel

`domenicrokers.nl` is een uitgebreid persoonlijk cv van Domenic Rokers: achtergrond, werkervaring, opleidingen en vaardigheden, aangevuld met interesses, eigen projecten en controleerbare statistieken. Bezoekers krijgen één samenhangend beeld van de persoon en zijn ontwikkeling. Er is geen aparte werkgeverssectie of commerciële dienstenpresentatie.

De inhoud blijft actueel doordat de eigenaar deze vanuit een webportaal kan aanpassen. Een nieuwe baan, een behaald certificaat of een nieuw project toevoegen vraagt geen codewijziging of deployment.

## Bezoekers en hun vragen

| Bezoeker | Wat die snel moet kunnen vinden |
| --- | --- |
| Recruiter of mogelijke werkgever | Profiel, relevante ervaring, vaardigheden, opleiding en contact |
| Mogelijke samenwerkingspartner | Projecten, eigen rol, aanpak, gebruikte technieken en resultaten |
| Andere ontwikkelaar | Technische keuzes, repositorylinks en developmentactiviteit |
| Eigenaar | Een duidelijke beheeromgeving en inzicht in wat publiek zichtbaar is |

De homepage geeft het overzicht. Het cv biedt de tijdlijn en feiten. Projectpagina's leveren verdieping en bewijs. Statistieken ondersteunen het verhaal met herkenbare, controleerbare cijfers.

## Productprincipes

1. **Persoonlijk en feitelijk.** Een herkenbare eigen stem, echte projecten en controleerbare informatie.
2. **Zelfstandig te beheren.** Alle afgesproken inhoud is via het portaal te wijzigen.
3. **Snel en toegankelijk.** Leesbaar op een telefoon, bruikbaar met een toetsenbord en zonder zware animaties.
4. **Passend bij de hosting.** Bouwen voor het bestaande Vimexx-pakket en Cloudflare-DNS.
5. **Stapsgewijs uitbreidbaar.** Eerst een complete kern, daarna externe koppelingen en aanvullende functies.

## Scope van de eerste release

| Onderdeel | Invulling |
| --- | --- |
| Profiel | Naam, eigen functietitel, introductie, biografie, foto, regio, openbare contactlinks |
| Cv | Werkervaring, opleidingen, diploma's/certificaten en vaardigheden |
| Projecten | Overzicht, detailpagina's, status, eigen rol, technieken, afbeeldingen en externe links |
| Development | Handmatig beheerde cijfers en tellingen uit gepubliceerde projectgegevens |
| Contact | Een door de eigenaar gekozen e-mailadres en/of externe profiel-links |
| Beheer | Eén beheerder, tweefactorauthenticatie, inhoudsbeheer, publicatie en mediabeheer |
| Vindbaarheid | Paginatitels, beschrijvingen, deelafbeeldingen, sitemap en canonieke URL's |
| Cv meenemen | Printweergave; opslaan als PDF via de browser |
| Bedrijfsvoering | Staging, reproduceerbare release, back-up en geoefend herstel |

## Vervolguitbreidingen

- Automatische GitHub-statistieken en een activiteitskalender.
- Een Nederlandstalige en Engelstalige versie, na een expliciete vertaalkeuze.
- Een blog of korte projectupdates als er voldoende inhoud voor is.
- Een contactformulier met berichtafhandeling en spambeperking.
- WakaTime of een andere bron van programmeertijd, als de eigenaar deze gebruikt en wil delen.
- Een automatisch gegenereerde, afzonderlijk vormgegeven cv-PDF.
- Privacybewuste bezoekersstatistieken als daar behoefte aan ontstaat.

Deze uitbreidingen zijn ideeën, geen onderdeel van de eerste oplevering of al geactiveerde diensten.

## Grenzen

Er komt in de eerste versie één eigenaar. Bezoekers hebben geen account nodig. Er zijn geen betalingen, vacatures, opdrachtadministratie of algemene websitebouwer nodig.

De site presenteert projecten zoals `globetrotr.nl`; de werking of hosting van die projecten verhuist daarmee niet naar dit project. Projectstatistieken zoals aantallen gebruikers verschijnen alleen wanneer daarvoor een betrouwbare bron en toestemming beschikbaar zijn.

## Wanneer de eerste versie geslaagd is

- Een bezoeker begrijpt op de homepage wie Domenic is en kan direct naar het cv, een project of contact.
- De eigenaar kan een cv-item en project aanmaken, ordenen, publiceren en weer verbergen zonder code te wijzigen.
- Een concept verschijnt niet op de publieke site, in de sitemap of in openbare previews.
- De website blijft bruikbaar als externe statistiekbronnen ontbreken.
- Alle openbare persoonlijke feiten zijn door de eigenaar gecontroleerd.
- De productieversie draait op het bestaande hostingaccount en kan worden hersteld uit een back-up.

De concrete controlepunten staan in het [functioneel ontwerp](functioneel-ontwerp.md) en de [roadmap](../roadmap.md).
