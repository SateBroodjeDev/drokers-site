<x-site-layout :footer-cta="false">
    <div class="resume-overview">
        <section class="container resume-heading" aria-labelledby="resume-name">
            <div><p class="eyebrow">{{ $page->title }}</p><h1 id="resume-name">{{ $profile['name'] }}</h1><p class="resume-heading__role">{{ $profile['headline'] }}</p></div>
            <a class="button button--accent" href="{{ route('cv.print') }}" target="_blank" rel="noopener">Bewaar mijn cv als PDF <x-site-icon name="print" /></a>
        </section>
        <nav class="container resume-nav resume-nav--compact" aria-label="Cv-onderdelen"><a href="#werkervaring">Werkervaring</a>@if($volunteering || $initiatives)<a href="#vrijwilligerswerk">Naast mijn werk</a>@endif<a href="#opleidingen">Opleidingen</a><a href="#vaardigheden">Vaardigheden</a></nav>
        <div class="container resume-layout resume-layout--compact">
            <div class="resume-content">
                <section class="resume-section" id="werkervaring">
                    <div class="resume-section__heading"><h2>Werkervaring</h2><span>{{ count($experiences) }} rollen</span></div>
                    <p class="resume-section__hint">Klik op een functie voor mijn werkzaamheden.</p>
                    <div class="resume-rows">@foreach(array_slice($experiences, 0, 3) as $entry)<x-resume-row :entry="$entry" />@endforeach</div>
                    @if(count($experiences) > 3)<details class="resume-history"><summary>Eerdere ervaring <span>{{ count($experiences) - 3 }} rollen</span><x-site-icon name="arrow-down" /></summary><div class="resume-rows">@foreach(array_slice($experiences, 3) as $entry)<x-resume-row :entry="$entry" />@endforeach</div></details>@endif
                </section>
                @if($volunteering || $initiatives)
                    <section class="resume-section" id="vrijwilligerswerk">
                        <div class="resume-section__heading"><h2>Naast mijn werk</h2></div>
                        <div class="resume-rows">@foreach(array_merge($volunteering, $initiatives) as $entry)<x-resume-row :entry="$entry" />@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="resume-aside" aria-label="Opleidingen, vaardigheden en talen">
                <section class="resume-section" id="opleidingen">
                    <div class="resume-section__heading"><h2>Opleidingen & diploma's</h2></div>
                    <div class="resume-qualifications">@foreach($education as $qualification)<x-resume-qualification :qualification="$qualification" :compact="true" />@endforeach</div>
                    @if($training)<details class="resume-history resume-training"><summary>Cursussen & trainingen <span>{{ count($training) }}</span><x-site-icon name="arrow-down" /></summary><div class="resume-qualifications">@foreach($training as $qualification)<x-resume-qualification :qualification="$qualification" :compact="true" />@endforeach</div></details>@endif
                </section>
                <section class="resume-section" id="vaardigheden">
                    <div class="resume-section__heading"><h2>Vaardigheden</h2></div>
                    <div class="resume-skill-grid">@foreach($skills as $group)<article class="resume-skill"><h3>{{ $group['name'] }}</h3><ul>@foreach($group['items'] as $skill)<li>{{ $skill }}</li>@endforeach</ul></article>@endforeach</div>
                    @if($languages)<h3 class="resume-subheading">Talen</h3><div class="resume-languages">@foreach($languages as $language)<article><span class="resume-language-code" aria-hidden="true">{{ $language['code'] }}</span><div><h3>{{ $language['name'] }}</h3><p>{{ $language['level'] }}</p></div></article>@endforeach</div>@endif
                </section>
            </aside>
        </div>
    </div>
</x-site-layout>
