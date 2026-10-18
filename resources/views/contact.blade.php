<x-site-layout :footer-cta="false">
    <div class="contact-page">
        <header class="container compact-page-heading"><p class="eyebrow">{{ $page->title }}</p><h1><x-heading-lines :text="$page->data['heading'] ?? 'Even kennismaken.'" /></h1></header>
        <section class="container contact-workspace" aria-label="Contact en kennismaken">
            <div class="contact-channels">
                <x-contact-form />
                @if($page->data['sections'] ?? [])<details class="contact-editorial"><summary>Meer informatie</summary>@foreach($page->data['sections'] as $section)<section>@if($section['title'] ?? '')<h2>{{ $section['title'] }}</h2>@endif<p>{{ $section['body'] ?? '' }}</p></section>@endforeach</details>@endif
            </div>
            <aside class="contact-person" aria-label="Met wie je contact opneemt">
                <div class="contact-person__identity">@if($photo)<img class="contact-person__photo" src="{{ $photo['url'] }}" width="{{ $photo['width'] }}" height="{{ $photo['height'] }}" alt="{{ $photo['alt'] }}">@else<img class="contact-person__mark" src="{{ $brandImage['url'] ?? asset('images/domenic-mark.svg') }}" width="56" height="56" alt="" aria-hidden="true">@endif<div><h2>{{ $profile['name'] }}</h2>@if($profile['headline'])<p>{{ $profile['headline'] }}</p>@endif</div></div>
                @if($site['traits'])<p class="contact-person__traits">{{ implode(' · ', $site['traits']) }}</p>@endif
                @if($site['links'])
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
                @endif
                @if($pages->has('page:cv'))<a class="text-link contact-resume-link" href="{{ route('cv') }}">Bekijk mijn cv <x-site-icon name="arrow-right" /></a>@endif
            </aside>
        </section>
    </div>
</x-site-layout>
