<x-site-layout :footer-cta="false">
    <div class="contact-page">
        <x-page-heading />
        <section class="container contact-workspace" aria-label="Contact en kennismaken">
            <div class="contact-channels">
                @if($site['links'])
                    <div class="contact-section-heading"><h2>{{ $site['contact_heading'] ?? 'Neem contact op.' }}</h2><p>{{ $site['contact_intro'] ?? 'Voor een kennismaking, een professionele vraag of een gesprek over wat ik doe.' }}</p></div>
                    <div class="contact-channel-list">
                        @foreach($site['links'] as $link)
                            @php($isEmail = str_starts_with(strtolower($link['url']), 'mailto:'))
                            @php($isGithub = strtolower(parse_url($link['url'], PHP_URL_HOST) ?? '') === 'github.com')
                            <a class="contact-channel" href="{{ $link['url'] }}" rel="noopener noreferrer">
                                <span class="contact-channel__icon"><x-site-icon :name="$isEmail ? 'mail' : ($isGithub ? 'code' : 'globe')" /></span>
                                <span class="contact-channel__copy"><span class="contact-channel__type">{{ $isEmail ? 'E-mail' : ($isGithub ? 'GitHub · openbare code' : 'Profiel of website') }}</span><strong>{{ $link['label'] }}</strong>@if(!empty($link['description']))<span class="contact-channel__description">{{ $link['description'] }}</span>@endif</span>
                                <x-site-icon :name="$isEmail ? 'arrow-right' : 'arrow-up-right'" />
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="contact-unavailable"><span class="contact-channel__icon"><x-site-icon name="mail" /></span><h2>Contactgegevens volgen.</h2><p>Er zijn op dit moment geen openbare contactgegevens beschikbaar. Je kunt wel alvast mijn achtergrond en ervaring bekijken.</p>@if($pages->has('page:cv'))<a class="text-link" href="{{ route('cv') }}">Bekijk mijn cv <x-site-icon name="arrow-right" /></a>@endif</div>
                @endif
                @if($page->data['sections'] ?? [])<div class="contact-editorial">@foreach($page->data['sections'] as $section)<section>@if($section['title'] ?? '')<h2>{{ $section['title'] }}</h2>@endif<p>{{ $section['body'] ?? '' }}</p></section>@endforeach</div>@endif
            </div>
            <aside class="contact-person" aria-label="Met wie je contact opneemt">
                <div class="contact-person__identity">@if($photo)<img class="contact-person__photo" src="{{ $photo['url'] }}" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" alt="{{ $photo['alt'] }}">@else<img class="contact-person__mark" src="{{ $brandImage['url'] ?? asset('images/domenic-mark.svg') }}" width="56" height="56" alt="" aria-hidden="true">@endif<div><h2>{{ $profile['name'] }}</h2>@if($profile['headline'])<p>{{ $profile['headline'] }}</p>@endif</div></div>
                @if($site['traits'])<p class="contact-person__traits">{{ implode(' · ', $site['traits']) }}</p>@endif
                @if($pages->has('page:about') || $pages->has('page:cv'))<nav class="contact-person__links" aria-label="Meer over mijn achtergrond">@if($pages->has('page:about'))<a href="{{ route('about') }}">De persoon achter het cv <x-site-icon name="arrow-right" /></a>@endif @if($pages->has('page:cv'))<a href="{{ route('cv') }}">Werkervaring & opleidingen <x-site-icon name="arrow-right" /></a><a href="{{ route('cv.print') }}">Cv afdrukken of bewaren <x-site-icon name="print" /></a>@endif</nav>@endif
                @if($site['show_development'] && $pages->has('page:development'))<div class="contact-code-link"><a class="text-link" href="{{ route('development') }}">Development & statistieken <x-site-icon name="arrow-right" /></a></div>@endif
                <div class="contact-share"><button class="text-link" type="button" data-contact-share data-copy="{{ url('/') }}" hidden>Deel mijn website <x-site-icon name="copy" /></button><p class="copy-fallback" data-copy-fallback hidden>{{ url('/') }}</p></div>
            </aside>
        </section>
    </div>
</x-site-layout>
