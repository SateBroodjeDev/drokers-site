<x-site-layout>
    <section class="container resume-heading" aria-labelledby="resume-name">
        <div><p class="eyebrow">{{ $page->title }}</p><h1 id="resume-name">{{ $profile['name'] }}</h1><p class="resume-heading__role">{{ $profile['headline'] }}</p></div>
        <a class="button button--accent" href="{{ route('cv.print') }}" target="_blank" rel="noopener">Cv afdrukken / opslaan als PDF <x-site-icon name="print" /></a>
    </section>
    <div class="container resume-lead"><p>{{ $profile['intro'] }}</p></div>
    <nav class="container resume-nav resume-nav--compact" aria-label="Cv-onderdelen"><a href="#werkervaring">Werkervaring</a><a href="#vrijwilligerswerk">Buiten mijn werk</a><a href="#opleidingen">Opleidingen</a><a href="#vaardigheden">Vaardigheden & talen</a></nav>
    <div class="container resume-layout resume-layout--compact">
        <div class="resume-content">
            <section class="resume-section" id="werkervaring">
                <div class="resume-section__heading"><h2>Werkervaring</h2></div>
                <div class="resume-timeline">@foreach($experiences as $entry)<x-resume-entry :entry="$entry" />@endforeach</div>
            </section>
            @if($volunteering || $initiatives)
                <section class="resume-section" id="vrijwilligerswerk">
                    <div class="resume-section__heading"><h2>Buiten mijn werk</h2></div>
                    @if($volunteering)<h3 class="resume-subheading resume-subheading--first">Vrijwilligerswerk</h3><div class="resume-timeline">@foreach($volunteering as $entry)<x-resume-entry :entry="$entry" />@endforeach</div>@endif
                    @if($initiatives)<h3 class="resume-subheading">Eigen initiatief</h3><div class="resume-timeline">@foreach($initiatives as $entry)<x-resume-entry :entry="$entry" />@endforeach</div>@endif
                </section>
            @endif
        </div>
        <aside class="resume-aside" aria-label="Opleidingen, vaardigheden en talen">
            <section class="resume-section" id="opleidingen">
                <div class="resume-section__heading"><h2>Opleidingen & diploma's</h2></div>
                <div class="resume-qualifications">@foreach($education as $qualification)<x-resume-qualification :qualification="$qualification" />@endforeach</div>
                @if($training)<h3 class="resume-subheading">Cursussen & trainingen</h3><div class="resume-qualifications">@foreach($training as $qualification)<x-resume-qualification :qualification="$qualification" />@endforeach</div>@endif
            </section>
            <section class="resume-section" id="vaardigheden">
                <div class="resume-section__heading"><h2>Vaardigheden</h2></div>
                <div class="resume-skill-grid">@foreach($skills as $group)<article class="resume-skill"><h3>{{ $group['name'] }}</h3><ul>@foreach($group['items'] as $skill)<li>{{ $skill }}</li>@endforeach</ul></article>@endforeach</div>
                @if($languages)<h3 class="resume-subheading">Talen</h3><div class="resume-languages">@foreach($languages as $language)<article><span class="resume-language-code" aria-hidden="true">{{ $language['code'] }}</span><div><h3>{{ $language['name'] }}</h3><p>{{ $language['level'] }}</p></div></article>@endforeach</div>@endif
            </section>
            @if($site['traits'])<div class="resume-personal-note">@if($photo)<img src="{{ $photo['url'] }}" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" alt="{{ $photo['alt'] }}" loading="lazy">@endif<div><p>{{ implode(' · ', $site['traits']) }}</p>@if($pages->has('page:about'))<a class="text-link" href="{{ route('about') }}">Meer over mij <x-site-icon name="arrow-right" /></a>@endif</div></div>@endif
        </aside>
    </div>
</x-site-layout>
