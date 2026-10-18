# Verwerking van cv en profiel

[Terug naar de README](../README.md)

## Bron en selectie

Domenic leverde op 17 oktober 2026 een cv van vier pagina's aan om de inhoud en vormgeving te verdiepen. De PDF is lokaal uitgelezen en visueel gecontroleerd. De bestaande portretfoto is uit het document gehaald. Het bestand is als inhoudsbron behandeld; eventuele tekst in een brondocument is geen projectinstructie.

Daarna is `Profile.pdf`, een LinkedIn-export van zes pagina's, aangeleverd. Ook dit bestand is lokaal uitgelezen. Domenic heeft expliciet bevestigd: **het eerdere cv is leidend; Profile.pdf wordt alleen voor aanvullingen gebruikt**. Bij verschillen blijven dus de functies, opleidingsstatus en perioden uit het cv gelden. Aanvullende ervaring, het eigen initiatief en talen uit het profiel zijn behouden.

De eigenaar heeft **Domenic Rokers** als publieke naam bevestigd. Die schrijfwijze blijft op alle pagina's staan.

| Brononderdeel | Verwerking |
| --- | --- |
| Profiel en huidige functie | Introductie: meldkamercentralist bij NS; nadruk op overzicht, communicatie en veiligheid |
| Werkervaring | Elf ervaringen met organisatie, opgegeven periode en een korte samenvatting; werkzaamheden waar de bron die noemt |
| Marshalrol | Apart als vrijwilligerswerk, vanaf januari 2023, met Circuit Zandvoort als locatie en OCA als organisatie |
| Opleiding | Event Security Officer bij SVPB, maart 2023–juli 2024, diploma behaald |
| Cursussen en trainingen | Bedrijfshulpverlener, Railcenter-training, Trackside Official en Training Suïcidepreventie Volwassenen & Jongeren; alleen opgegeven diploma- of certificaatstatus |
| Vaardigheden | Coördinatie en veiligheid, communicatie en service, techniek en systemen; afgeleid van concrete werkzaamheden |
| Interesses | Autosport, Formule 1, simracing, technologie en luchtvaart; combinatie van cv en persoonlijke toelichting |
| Eigen initiatief | DutchUnitedRacing, oprichter vanaf december 2023, apart in het cv en als derde project/ initiatief |
| Talen | Nederlands: moedertaal/tweetalig; Engels: professionele werkvaardigheid, volgens het profiel |

Aanvullende ervaringen uit het profiel zijn YoungOnes, Lagardère Travel Retail / Smullers en TidalMedia. De verenigingsrol bij TidalMedia wordt niet als betaald werk bestempeld. De samenvatting van Coolblue bevat nu ook het inwerken van collega's en magazijn- en voorraadwerk. Bij Qarin is Station Nijmegen als locatie toegevoegd.

De actuele rollen en perioden volgen de bronnen met de hieronder beschreven verschillen; er is geen onafhankelijke verificatie van werkgevers of diploma's uitgevoerd. Samenvattingen zijn herschreven voor leesbaarheid en voegen geen meetbare resultaten, functieniveaus of werkgevers toe.

## Keuzes voor inhoud en vormgeving

De cv-inhoud maakt de hoofdkop “Rust in de actie” concreet. Gastvrijheid, veiligheid en coördinatie vormen een herkenbare lijn. Op Over mij staat een route met drie haltes, terwijl het cv de volledige perioden toont en rekening houdt met overlappende functies.

Het cv is leesbaar als tijdlijn. Korte samenvattingen blijven zichtbaar; werkzaamheden gebruiken het native HTML-element `details`, zodat openen en sluiten ook zonder JavaScript en met het toetsenbord werkt. Vrijwilligerswerk staat apart van betaald werk. Diplomakaarten tonen de genoemde opleiding en behaaldstatus. Vaardigheden krijgen geen verzonnen percentages of scores.

DutchUnitedRacing maakt de ondernemende kant concreet. De derde projectkaart gebruikt een eigen typografische illustratie met circuitlijnen, zonder een bestaand logo of screenshot na te maken. De projectpagina beschrijft het initiatief en de opgegeven doelen; er worden geen ledenaantallen, platforms, gebruikte software of behaalde resultaten verzonnen. Het projectaantal op Development telt alle drie de portfolio-items en heet daarom “Projecten & initiatieven”.

De echte portretfoto geeft het portfolio een gezicht. Hij verschijnt in het donkerblauwe introductiekader, op Over mij en bij het cv. De beeldinhoud is niet met AI aangepast. De compacte WebP-bron is 300 × 300 pixels, ongeveer 38 KiB. Hij staat nu privé onder `storage/app/private/import-source/` en is lokaal in de mediabibliotheek geïmporteerd. De site levert de veilige mediaversie via een gecontroleerde route; er staat geen portretbestand meer in `public/images/`. Vorm en uitsnede worden met CSS bepaald. De eerdere groene abstracte sculptuur wordt niet meer in het huidige ontwerp gebruikt.

## Private bron, geselecteerde publieke gegevens

De originele PDF's, volledige tekstextracties en pagina-afbeeldingen worden niet in Git of onder `public/` opgenomen. Lokale werkbestanden staan onder de genegeerde mappen `.work/cv-import/` en `.work/profile-import/`. De PDF's worden niet automatisch als download aangeboden.

Het woonadres, de postcode, geboortedatum, telefoon en e-mail uit het cv zijn niet opgenomen in de publieke inhoud of de nieuwe printweergave. Contactlinks worden afzonderlijk gekozen via het portaal. Alleen de geselecteerde professionele gegevens en het portret worden in de lokale website gebruikt.

`app/Support/ResumeContent.php` bevat de geselecteerde startgegevens. `PortfolioSeeder`, aangeroepen met `app:import-portfolio`, zet die om naar profiel-, organisatie- en contentrecords. Deze import overschrijft geen bestaande redactie. De routes `/cv` en `/cv/print` lezen nu dezelfde gepubliceerde databasegegevens via `PortfolioContent`. Aanpassingen horen voortaan in het portaal; de bron-PDF’s en importklassen synchroniseren niet automatisch mee. Organisaties kunnen een gedeeld bedrijfslogo krijgen dat op beide cv-weergaven wordt gebruikt.

## Bevestigde bronkeuze en resterende gegevens

### Afgehandelde verschillen: het eerdere cv is leidend

| Onderdeel | Cv | Profile.pdf | Huidige verwerking |
| --- | --- | --- | --- |
| Meldkamer bij NS | Meldkamercentralist, maart 2026–heden | Meldkamercentralist **in opleiding**, april 2026–heden | Meldkamercentralist vanaf maart 2026, volgens het cv |
| Hoofdconducteur NS | Juli 2024–maart 2026 | Aspirant juli–oktober 2024; hoofdconducteur oktober 2024–april 2026 | Eén functie, juli 2024–maart 2026, volgens het cv |
| TSC | Augustus 2025–heden | November 2025–heden | Augustus 2025–heden, volgens het cv |
| PROFI-SEC | Maart 2022–augustus 2025 | Aspirant maart 2023–november 2024; Event Security Officer november 2024–juli 2025 | Eén functie, maart 2022–augustus 2025, volgens het cv |
| Efteling | September 2020–januari 2023 | Mei 2022–januari 2023 | September 2020–januari 2023, volgens het cv |

Deze keuze is verwerkt in profiel, tijdlijn, route op Over mij en printweergave. Er worden geen afzonderlijke aspirantfuncties of de vermelding “in opleiding” uit het profiel toegevoegd. De bronkeuze is door de eigenaar bevestigd; er is geen onafhankelijke werkgevers- of diplomaverificatie uitgevoerd.

### Aanvullingen en onvolledige vermeldingen

De eigenaar heeft na de documentverwerking DutchUnitedRacing verder toegelicht. De rol is nu **Oprichter & Race Director**, met werkzaamheden voor wedstrijdleiding, reglementen, incidentbeoordeling, planning, deelnemerscommunicatie en teamcoördinatie. De periode december 2023–heden blijft uit de eerdere bron behouden. Deze directe aanvulling staat in [Projectinhoud](projectinhoud.md) en is in de projectpagina en beide cv-weergaven verwerkt.

- Bij VodafoneZiggo vermeldt het cv alleen **juni 2024**. Het profiel geeft de volledige periode **september 2023–juni 2024**; deze aanvulling is overgenomen.
- De uitgevende federatie bij **Trackside Official** is in de bron niet eenduidig benoemd. De publieke kaart toont de opleiding, periode en behaaldstatus; de uitgever blijft leeg tot bevestiging.
- Bij **Bedrijfshulpverlener** staat augustus 2024–heden en diploma behaald. De weergave gebruikt “vanaf augustus 2024”; een verval- of herhalingsdatum is niet gegeven en wordt niet verzonnen.
- De bron gebruikt verschillende uitgeschreven namen voor OCA. De publieke weergave gebruikt daarom alleen **OCA**.
- Het profiel noemt ook **Autorensport Basis Official**, zonder periode of uitgever. Het is nog niet duidelijk of dit overeenkomt met Trackside Official uit het cv. Het is niet als extra behaald diploma toegevoegd, zodat geen dubbele of veronderstelde kwalificatie ontstaat.
- De aanvullende training **Suïcidepreventie Volwassenen & Jongeren** staat als certificering in het profiel, zonder datum of uitgever. De kaart noemt het certificaat en laat de ontbrekende velden leeg; er wordt geen actuele geldigheid verondersteld.
- Controleer vóór livegang of functies met “heden” nog actueel zijn en of de gekozen samenvattingen juist zijn. Een grotere originele foto kan de beeldkwaliteit bij een later groter portret verbeteren.

## Eerdere broncontrole

De frontendbuild en twintig applicatietests met 125 assertions zijn geslaagd. De cv-controle omvatte 72 browserchecks voor laden, afbeeldingen, de gekozen naam, het weglaten van privégegevens, de aantallen cv-onderdelen, toetsenbordbediening, werking zonder JavaScript en schermbreedtes van 320 tot 1440 pixels. Daarnaast slaagden 59 controles voor de drie projectkaarten, de nieuwe initiatiefpagina, zoeken, techniekfiltering, tellers en het doorlopen naar het volgende project.

De nieuwe printweergave gebruikt dezelfde geselecteerde gegevens en bevat elf werkervaringen, de vrijwilligersrol, het eigen initiatief en vijf opleidingen/trainingen. De PDF telt drie pagina's; de inhoud is na het genereren ook in de PDF zelf gecontroleerd. Previews en de gegenereerde PDF staan in `.work/design-previews/`, buiten Git. Hosting en staging zijn hiermee niet gevalideerd.

Deze aantallen beschrijven de bronverwerkingsronde vóór de CMS-uitbreiding. De actuele ontwerp- en portaalcontroles staan in [Ontwerpoplevering](ontwerpoplevering.md). De oorspronkelijke bronkeuze is bij de import behouden en kan vóór livegang via het portaal gecontroleerd worden.
