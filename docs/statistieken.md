# Developmentstatistieken

[Terug naar de README](../README.md)

## Doel en bouwvolgorde

Cijfers laten zien waaraan Domenic werkt en ondersteunen het portfolio. Elk cijfer krijgt een duidelijke betekenis, bron en peildatum of periode. Aantallen commits of taalverdeling worden niet gepresenteerd als een algemene kwaliteitsscore.

De site bevat handmatige cijfers, intern berekende tellers en inmiddels een eerste GitHub-koppeling voor openbare repositorycijfers. De eigenaar heeft `SateBroodjeDev` als profiel bevestigd. Extra diensten volgen alleen bij een concrete behoefte.

## Nu geïmplementeerd

| Statistiek | Definitie | Bron |
| --- | --- | --- |
| Gepubliceerde projecten | Aantal projecten met publieke status, zonder logische verwijdering | Eigen database |
| Eigen cijfer | Door de eigenaar opgegeven getal met label, eenheid, bron en datum | Handmatige invoer |
| Openbare eigen repositories | Publieke repositories waarvan het ingestelde account eigenaar is, zonder forks en archieven | Opgeslagen GitHub REST-meting |
| Ontvangen sterren | Som van sterren op diezelfde repositoryset; geen unieke personen | Opgeslagen GitHub REST-meting |

De projectteller op Development heet **Projecten & initiatieven** en telt `projects` met status `published`, een verstreken publicatiemoment en zonder logische verwijdering. De geïmporteerde startinhoud bevat Globetrotr, deze persoonlijke website en DutchUnitedRacing. Het is geen telling van GitHub-repositories.

Eigen cijfers worden beheerd onder **Cv, pagina’s & cijfers → Statistiek**, opgeslagen in `content_records` en na publicatie op Development getoond. Waarde, bronvermelding en peildatum zijn verplicht; eenheid en een HTTP(S)-bronlink zijn optioneel. Zie [Je website beheren](beheerportaal.md). Er zijn geen verzonnen handmatige cijfers vooringevuld.

De kalender blijft een zichtbare demonstratie en toont geen echte GitHub-activiteit. De schakelaar voor dertien of tweeënvijftig weken verandert alleen die demonstratie. De nieuwe koppeling meet repositories en sterren, geen commits, bijdragekalender of taalverdeling. Die drie onderdelen zijn nog niet als echte meting gebouwd.

### Instellen en synchroniseren

Na de nieuwe migration staat de tabel `github_snapshots` klaar. Stel onder **Profiel & vormgeving → Contact & footer** de gebruikersnaam in en schakel openbare GitHub-statistieken in. Sla op en kies **GitHub bijwerken** boven het profiel. De bevestigde eigenaar kan ook via CLI worden ingesteld, zonder overige inhoud of publicatiestatus te wijzigen:

```bash
php artisan app:configure-github SateBroodjeDev --statistics
php artisan app:sync-github
```

Een profiel-link verschijnt op Contact en in de footer; de meting verschijnt op home en Development als Development zichtbaar is. Een nieuw account toont geen oude meting van een ander account. Een ongepubliceerd profiel levert geen openbare GitHub-gegevens op. Statistieken uitschakelen verbergt de meting maar laat een ingestelde openbare profiel-link bestaan.

De server leest alle pagina's van de repositorylijst (100 per pagina), dedupliceert op GitHub-ID en valideert elk record. Na maximaal 100 pagina's of een totaalbudget van circa 45 seconden plus een lopend request wordt een onvolledig resultaat geweigerd. Requests hebben een verbindingslimiet van 4 seconden en een totale requestlimiet van 10 seconden. Er is een accountlock van 180 seconden en minimaal vijf minuten tussen pogingen. Rate-limitantwoorden stellen een nieuwe poging uit; fouten vervangen de vorige geldige telling niet door nul. Alleen een volledige uitkomst wordt gepubliceerd. De site leest de opgeslagen meting, zonder API-verzoek tijdens een paginalaadactie.

Er is één actuele snapshot per account met laatste poging, succes, foutcategorie en eerstvolgende toegestane poging. Er is nog geen twaalfmaandelijkse meethistoriek of taalbytes-aggregatie. Een meting ouder dan 48 uur krijgt een zichtbare melding. De API-endpointdefinitie staat in de [officiële GitHub-documentatie](https://docs.github.com/en/rest/repos/repos#list-repositories-for-a-user).

`routes/console.php` plant de CLI-synchronisatie iedere zes uur zonder overlap. Hiervoor moet op Vimexx nog een geteste cronregel `artisan schedule:run` aanroepen; een geplande taak in code bewijst niet dat hostingcron werkt. Publieke gegevens werken zonder token; optioneel kan `GITHUB_TOKEN` alleen op de server worden ingesteld. Geen tokens in het portaal, Git of de browser.

De eerste echte synchronisatie voor het door de eigenaar bevestigde profiel `SateBroodjeDev` is lokaal geslaagd. De opgeslagen meting gaf één openbare eigen repository en nul sterren. Deze aantallen zijn een momentopname; de publieke pagina leest de database en bevat geen hardgecodeerde GitHub-cijfers. De tests gebruiken gesimuleerde antwoorden en hebben geen live API nodig. Op Vimexx zijn deze migration en synchronisatie nog niet uitgevoerd.

Intern berekende cijfers volgen de actuele gepubliceerde inhoud. Een gepubliceerd project intrekken verandert ook de tellers. Een eigen handmatig cijfer verandert alleen na een bewuste beheeractie.

## Verdere GitHub-uitbreiding — meetdefinities

| Statistiek | Voorgestelde meetdefinitie |
| --- | --- |
| Openbare eigen repositories — gebouwd | Unieke publieke repositories waarvan het ingestelde account eigenaar is; forks en gearchiveerde repositories uitgesloten |
| Ontvangen sterren — gebouwd | Som van sterren op diezelfde repositories; dit is een aantal sterren, geen aantal unieke personen |
| Taalverdeling | Som van taalbytes over de gekozen repositoryset, gedeeld door het totaal aan taalbytes |
| Bijdragenkalender | GitHub-bijdragen voor de afgesproken periode, met een label dat duidelijk “bijdragen” zegt |
| Commitbijdragen | Alleen indien apart gewenst: het daarvoor bedoelde GitHub-veld, met expliciete periode en zichtbaarheid |

De repositoryset en filters worden zichtbaar toegelicht en als versie van de meetdefinitie vastgelegd. Accountbrede bijdragecijfers krijgen hun eigen label; ze worden niet voorgesteld als een telling binnen de gefilterde repositoryset.

De [GitHub REST API voor repositorytalen](https://docs.github.com/en/rest/repos/repos#list-repository-languages) rapporteert bytes per taal. Dat meet de samenstelling van code en is geen meting van programmeertijd, expertise of geschreven regels.

De [GitHub GraphQL-definitie van bijdragen](https://docs.github.com/en/graphql/reference/users#contributionscollection) bevat verschillende bijdragevormen. De algemene kalender wordt daarom niet “commits” genoemd. We volgen de zichtbaarheid en meetregels van GitHub en leggen de gekozen periode vast.

## Ophalen en publiceren

1. De eigenaar configureert een GitHub-gebruikersnaam en schakelt de koppeling bewust in.
2. Een servertaak haalt alleen de voor de gekozen statistieken noodzakelijke gegevens op.
3. Alle pagina's van een gepagineerde repositorylijst worden verwerkt; deduplicatie gebeurt op GitHub-ID.
4. De server valideert de uitkomst en schrijft een complete geslaagde meting weg.
5. De publieke site leest uitsluitend de opgeslagen, vrijgegeven statistieken.

De eerste koppeling richt zich op openbare informatie. Gebruik een token met zo weinig mogelijk rechten als authenticatie nodig is. De exacte tokenvorm en vereiste rechten worden bij implementatie tegen de API gecontroleerd. De aanwezigheid van een token geeft nooit automatisch toestemming om privéprojecten of privéactiviteit te publiceren.

Credentials staan uitsluitend in de serverconfiguratie. De publieke browser, beheerschermen, logs en repository krijgen het token niet te zien. De beheerder ziet alleen of de koppeling is geconfigureerd en wanneer de laatste poging en het laatste succes plaatsvonden.

## Frequentie en foutafhandeling

Voorstel: elke zes uur synchroniseren. De scheduler kan frequenter worden aangeroepen; alleen een vervallen synchronisatie start de API-taak. Een handmatige verversactie in het portaal gebruikt dezelfde lock en krijgt een afkoelperiode van vijf minuten.

| Situatie | Publieke weergave | Beheerportaal |
| --- | --- | --- |
| Nog geen geslaagde meting | Statistiek verbergen of “nog geen gegevens” | Uitleg dat configuratie of eerste synchronisatie ontbreekt |
| Succesvolle meting | Waarde, definitie en peildatum | Laatste succes en meetperiode |
| Tijdelijke API-fout | Laatste geldige waarde met de bestaande peildatum | Beknopte foutcategorie en volgende poging |
| Langer dan 48 uur geen succes | “Laatst bijgewerkt op …” met melding dat gegevens verouderd zijn | Duidelijke waarschuwing |
| Rate limit bereikt | Laatste geldige waarde | Wachten tot toegestaan hervatten; geen herhaalde directe retries |
| Token ongeldig | Laatste geldige waarde, indien aanwezig | Configuratiefout; automatische herhaling beperken |
| Ongeldig of onvolledig antwoord | Vorige complete meting behouden | Validatiefout; onvolledig resultaat niet publiceren |
| Koppeling uitgeschakeld | De bijbehorende externe statistieken verbergen | Historiek blijft intern bewaard tot opruiming |

Stel connectie- en requesttime-outs in, beperk de totale looptijd en voorkom gelijktijdige synchronisaties. Respecteer `Retry-After` en rate-limitheaders. Een API-fout mag niet als nul commits of nul projecten worden opgeslagen.

## Bewaren en toegankelijk presenteren

Bewaar geslaagde snapshots voorlopig maximaal twaalf maanden en technische synchronisatielogs dertig dagen; maak dit bij de bouw configureerbaar. Voor een kalender wordt alleen het benodigde dagtotaal opgeslagen, zonder privérepositorynamen of commitinhoud.

Grafieken krijgen naast kleur een leesbare toelichting en een tekstuele of tabelweergave van de belangrijke waarden. Cijfers krijgen een Nederlandse getalnotatie en een herkenbare meetdatum.

## Acceptatiecriteria voor de koppeling

- Ontbrekende credentials breken publieke pagina's niet.
- Gepagineerde testgegevens worden volledig verwerkt, zonder dubbeltellingen.
- Forks, archieven, privégegevens en meetperiodes volgen de afgesproken definitie.
- Een mislukt verzoek laat de laatste geslaagde meting intact.
- Rate limits en gelijktijdige verversacties leiden niet tot een verzoeklus.
- Tokens en privépayloads verschijnen niet in HTML, loguitvoer of foutmeldingen.
- Het uitschakelen van de koppeling verbergt de bijbehorende publieke cijfers.

HTTP-antwoorden worden in automatische tests gesimuleerd. Tests van de website doen geen live GitHub-aanroepen.
