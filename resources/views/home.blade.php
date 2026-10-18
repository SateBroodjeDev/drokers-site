<x-site-layout>
    @php($portrait = $heroImage ?? $photo)
    <section class="container person-intro" aria-labelledby="introduction-name">
        <div class="person-intro__copy">
            <p class="person-greeting">{{ $site['welcome'] ?? 'Hoi, ik ben' }} <span aria-hidden="true">↗</span></p>
            <h1 id="introduction-name">{{ $profile['name'] }}<span class="person-period" aria-hidden="true">.</span></h1>
            @if($profile['headline'])<p class="person-role">{{ $profile['headline'] }}</p>@endif
            @if($profile['intro'])<p class="person-intro__text">{{ $profile['intro'] }}</p>@endif
            <div class="button-row">
                @if($pages->has('page:cv'))<a class="button" href="{{ route('cv') }}">Bekijk mijn cv <x-site-icon name="arrow-right" /></a>@endif
                @if($pages->has('page:contact'))<a class="button button--outline" href="{{ route('contact') }}">Zeg hallo <x-site-icon name="arrow-right" /></a>@elseif($pages->has('page:about'))<a class="text-link" href="{{ route('about') }}">Meer over mij <x-site-icon name="arrow-right" /></a>@endif
            </div>
            @if($pages->has('page:cv'))<a class="person-quiet-link" href="{{ route('cv.print') }}">Cv afdrukken / opslaan als PDF <x-site-icon name="print" /></a>@endif
        </div>
        <div class="person-intro__aside">
            @if($portrait)
                <figure class="person-portrait" data-pointer-depth>
                    <div class="person-portrait__image"><img src="{{ $portrait['url'] }}" width="{{ $portrait['width'] }}" height="{{ $portrait['height'] }}" alt="{{ $portrait['alt'] }}" fetchpriority="high"></div>
                    @if($site['hero_note'])<figcaption>{{ $site['hero_note'] }}</figcaption>@endif
                </figure>
            @else
                <aside class="profile-summary" aria-label="Mijn profiel">
                    <div class="profile-summary__identity"><img src="{{ $brandImage['url'] ?? asset('images/domenic-mark.svg') }}" width="48" height="48" alt="" aria-hidden="true"><div><h2>{{ $profile['name'] }}</h2>@if($profile['headline'])<p>{{ $profile['headline'] }}</p>@endif</div></div>
                    @if($site['traits'])<div class="profile-summary__qualities"><p>Wat je van mij kunt verwachten</p><ul>@foreach($site['traits'] as $trait)<li><x-site-icon name="check" />{{ $trait }}</li>@endforeach</ul></div>@endif
                    @if($pages->has('page:about'))<a class="text-link" href="{{ route('about') }}">Mijn achtergrond en motivatie <x-site-icon name="arrow-right" /></a>@endif
                </aside>
            @endif
            @if($site['interests'])<p class="person-interests"><span>Naast mijn werk</span>{{ implode(' · ', $site['interests']) }}</p>@endif
        </div>
    </section>

    @if($site['traits'])
        <div class="container person-traits" aria-label="Eigenschappen">@foreach($site['traits'] as $trait)<span><x-site-icon name="spark" />{{ $trait }}</span>@endforeach</div>
    @endif

    @if($site['show_world'] && $worlds->isNotEmpty())<x-interest-cards />@endif

    @if($pages->has('page:cv'))
        <section class="container person-career" aria-labelledby="career-heading">
            <div class="person-section-intro" data-reveal>
                <p class="eyebrow">Werk & ervaring</p>
                <h2 id="career-heading">{{ $site['career_heading'] ?? 'Mijn achtergrond.' }}</h2>
                <p>{{ $pages->get('page:cv')->summary }}</p>
                <a class="text-link" href="{{ route('cv') }}">Mijn volledige cv <x-site-icon name="arrow-right" /></a>
                <a class="person-quiet-link" href="{{ route('cv.print') }}">Print mijn cv of bewaar het als PDF <x-site-icon name="arrow-right" /></a>
            </div>
            <div class="person-experiences">
                @forelse($careerPreview as $experience)
                    <article class="person-experience" data-reveal>
                        <div class="person-experience__top"><p>{{ $experience['period'] }}</p>@if($site['show_organizations'] && $experience['logo'])<img @class(['person-experience__logo', 'organization-mark--dark' => $experience['logo']['background'] === 'dark']) src="{{ $experience['logo']['url'] }}" alt="{{ $experience['organization'] }}" loading="lazy">@endif</div>
                        <h3>{{ $experience['role'] }}</h3>
                        @if($experience['organization'])<p class="person-experience__organization">{{ $experience['organization'] }}@if($experience['location'])<span> · {{ $experience['location'] }}</span>@endif</p>@endif
                        @if($experience['summary'])<p class="person-experience__summary">{{ $experience['summary'] }}</p>@endif
                    </article>
                @empty
                    <a class="person-resume-link" href="{{ route('cv') }}"><x-site-icon name="education" /><span>Werkervaring, opleidingen en vaardigheden</span><x-site-icon name="arrow-right" /></a>
                @endforelse
            </div>
        </section>
    @endif

    @if($pages->has('page:projects') && $projects->isNotEmpty())
        <section class="section container person-projects" id="projecten" aria-labelledby="initiatives-heading">
            <div class="section-heading" data-reveal><div><p class="eyebrow">Wat ik daarnaast doe</p><h2 id="initiatives-heading">{{ $site['initiatives_heading'] ?? 'Mijn eigen initiatieven.' }}</h2></div><a class="text-link" href="{{ route('projects') }}">Ontdek mijn projecten <x-site-icon name="arrow-right" /></a></div>
            <div class="person-project-grid">@foreach($projects as $project)<x-project-card :project="$project" :personal="true" />@endforeach</div>
        </section>
    @endif

    @if($site['show_development'] && $pages->has('page:development'))
        <x-github-statistics />
        <section class="container person-development"><x-site-icon name="code" /><div><h2>{{ $pages->get('page:development')->title }}</h2></div><a class="text-link" href="{{ route('development') }}">Code & statistieken <x-site-icon name="arrow-right" /></a></section>
    @endif
    @if(!empty($page->data['sections']))<section class="section container prose-page">@foreach($page->data['sections'] as $section)<section><h2>{{ $section['title'] ?? '' }}</h2><p>{{ $section['body'] ?? '' }}</p></section>@endforeach</section>@endif
</x-site-layout>
