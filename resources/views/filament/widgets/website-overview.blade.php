<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Jouw website. Jouw verhaal.</x-slot>
        <x-slot name="description">Begin bij je profiel voor de uitstraling. Upload bedrijfslogo’s bij Afbeeldingen en koppel ze bij Organisaties. Bewaar nieuwe inhoud als concept, bekijk het voorbeeld en publiceer wanneer het klopt.</x-slot>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px">
            @foreach($counts as $label => $count)<div style="padding:16px;border:1px solid #b8c0ce55;border-radius:10px"><span style="font-size:13px;opacity:.75">{{ $label }}</span><strong style="display:block;font-size:30px;margin-top:6px">{{ $count }}</strong></div>@endforeach
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px">
            @foreach($links as [$label,$description,$url])<a href="{{ $url }}" style="display:block;padding:20px;border:1px solid #b8c0ce55;border-radius:10px"><strong>{{ $label }} ↗</strong><p style="font-size:13px;opacity:.75;margin-top:8px;line-height:1.6">{{ $description }}</p></a>@endforeach
        </div>
        <p style="font-size:13px;margin-top:20px;line-height:1.6">Een gepubliceerd onderdeel wijzig je met <strong>Opslaan en publiceren</strong>. Met <strong>Terug naar concept</strong> haal je het direct van de website. Afbeeldingen die nog in gebruik zijn, kun je niet verwijderen.</p>
        <div style="margin-top:20px"><x-filament::button tag="a" href="{{ route('home') }}" target="_blank">Bekijk mijn website</x-filament::button></div>
    </x-filament::section>
</x-filament-widgets::widget>
