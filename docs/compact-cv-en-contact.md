# Compact cv en contactformulier

[Terug naar de README](../README.md)

Deze wijziging is lokaal gebouwd na de door de eigenaar bevestigde release `20261017T220220Z`. Het nieuwe pakket is voorbereid; een geslaagde deployment van deze update op Vimexx is nog niet bevestigd. Een Git-commit of push activeert de hostingrelease niet.

## Wat verandert er?

- Het cv opent met drie compacte functieregels. Werkzaamheden verschijnen na openklappen; eerdere ervaring staat in een afzonderlijke uitklapgroep. De gegevens blijven volledig beschikbaar in de printversie.
- Vaardigheden zijn korte labels. Trainingen en toelichtingen kunnen worden opengeklapt. Dezelfde CMS-inhoud blijft de bron; er worden geen ervaringen verwijderd of teksten in de database overschreven.
- De contactpagina heeft een echt formulier. Berichten komen in **Contactberichten** in het portaal. De beheerder kan lezen, afhandelen, verwijderen en via het eigen e-mailprogramma antwoorden.
- De grote footeruitnodiging is vervangen door een compacte contactlink. De developmentverwijzing op de homepage bevat geen extra uitlegparagraaf meer.

## Beveiliging en gegevens

Berichten zijn niet gepubliceerd en hebben geen publieke leesroute. Actieve beheerrechten en MFA zijn verplicht. Er zijn validatie, CSRF, een honeypot, een limiet per minuut en per dag en escaping van bezoekersinhoud. Een ontvangstmelding volgt pas na opslag. Er worden geen e-mails verzonden en geen bijlagen verwerkt. De contactpagina wordt niet publiek gecachet.

De dagelijkse Laravel-scheduler verwijdert berichten ouder dan 90 dagen. Daarvoor moet hostingcron werken; handmatige verwijdering en het prune-commando blijven beschikbaar. De privacy-pagina beschrijft het gebruik van de formuliergegevens.

## Controles

- 56 applicatietests met 493 assertions geslaagd, inclusief versturen, afscherming, afhandeling, verwijderen, spambeperking, escaping en opschoning.
- Frontendbuild geslaagd.
- 110 lokale browsercontroles zonder fouten: mobiel en desktop van 320 tot 1440 pixels, licht/donker, openklappen, zonder JavaScript, formulierverzending en weigeren van een POST zonder CSRF-token.
- Standaard cv-hoogte bij 1440 pixels breed: ongeveer 1400 pixels in de lokale testinhoud. Uitklappen en CMS-wijzigingen beïnvloeden dit.
- Tijdelijk browserbericht na de controle uit de lokale database verwijderd. Screenshots staan uitsluitend onder `.work/design-previews/`.

## Volgende release

Er is één nieuwe migration: `2026_10_20_000001_create_contact_messages_table`. Maak vóór de productiemigratie een databaseback-up. Activeer de code pas nadat de tabel bestaat; het portaal gebruikt de tabel voor zijn inbox en tellers.

De huidige `update-hosting.sh` is aangepast voor de stap van de eerdere GitHub-release naar deze contactrelease. Hij staat uitsluitend de aanvullende contact-migration toe. Upload deze bijgewerkte hulp opnieuw; het eerder geüploade exemplaar is niet geschikt. Volg de [releaseprocedure voor het compacte cv en contactformulier](release-contactformulier.md). Behoud de productieconfiguratie, applicatiesleutel, accounts en uploads. Controleer op hosting formulierverzending, de portaalinbox, antwoordactie, HTTPS, caching en scheduler; lokale controles bewijzen deze hostingstappen niet.
