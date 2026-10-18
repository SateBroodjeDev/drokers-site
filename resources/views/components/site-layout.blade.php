@props(['title' => null, 'description' => null, 'footerCta' => true])
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? $page->summary }}"><meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="{{ $site['palette']['navy'] }}"><title>{{ $title ?? $page->title }} — {{ $profile['name'] }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="type-{{ $site['typography'] }}" data-appearance="{{ $site['appearance'] }}" data-motion="{{ $site['motion'] ? 'on' : 'off' }}" style="--paper: {{ $site['palette']['background'] }}; --ink: {{ $site['palette']['ink'] }}; --navy: {{ $site['palette']['navy'] }}; --accent: {{ $site['palette']['accent'] }}">
    <x-appearance-script />
    <a class="skip-link" href="#inhoud">Naar de inhoud</a>
    @if($isPreview)<div class="preview-ribbon">Beheervoorbeeld · nog niet openbaar <span class="preview-ribbon__label">Ontwerpversie</span></div>@endif
    <header class="site-header" data-header><div class="reading-progress" aria-hidden="true" data-reading-progress></div><div class="container header-inner">
        <a class="identity" href="{{ route('home') }}" aria-label="{{ $profile['name'] }}, home">@if($brandImage)<img class="brand-image" src="{{ $brandImage['url'] }}" alt="{{ $brandImage['alt'] }}">@else<img class="brand-image" src="{{ asset('images/domenic-mark.svg') }}" width="45" height="45" alt="" aria-hidden="true">@endif<span class="identity__name">{{ $profile['name'] }}</span></a>
        <nav class="site-nav" id="site-nav" aria-label="Hoofdnavigatie">@foreach(['about' => 'Over mij', 'cv' => 'Cv', 'projects' => 'Projecten', 'contact' => 'Contact'] as $routeName => $label)@if($pages->has('page:'.$routeName))<a href="{{ route($routeName) }}" @class(['nav-link', 'is-active' => $page->key === 'page:'.$routeName || ($routeName === 'about' && $page->key === 'page:home')]) @if($page->key === 'page:'.$routeName) aria-current="page" @endif>{{ $label }}</a>@endif @endforeach</nav>
        <div class="header-controls">
            <button class="appearance-toggle" type="button" data-appearance-toggle aria-label="Lichte weergave inschakelen" aria-pressed="false" hidden><span data-appearance-icon="light"><x-site-icon name="sun" /></span><span data-appearance-icon="night" hidden><x-site-icon name="moon" /></span><span class="sr-only" data-appearance-label>Lichte weergave inschakelen</span></button>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-menu-toggle><span data-menu-label>Menu</span><x-site-icon name="menu" /></button>
        </div>
    </div></header>
    <noscript><nav class="container no-script-nav" aria-label="Navigatie zonder JavaScript">@foreach(['home' => 'Home', 'about' => 'Over mij', 'cv' => 'Cv', 'projects' => 'Projecten', 'development' => 'Development', 'contact' => 'Contact'] as $routeName => $label)@if($pages->has('page:'.$routeName) && ($routeName !== 'development' || $site['show_development']))<a href="{{ route($routeName) }}">{{ $label }}</a>@endif @endforeach</nav></noscript>
    <main id="inhoud" tabindex="-1">{{ $slot }}</main>
    <footer class="site-footer site-footer--compact">
        <div class="container footer-main"><div><a class="footer-wordmark" href="{{ route('home') }}">{{ $profile['name'] }}<span>.</span></a>@if($site['footer_line'])<p>{{ $site['footer_line'] }}</p>@endif</div><div class="footer-links">@foreach($site['links'] as $link)<a href="{{ $link['url'] }}" rel="noopener noreferrer">{{ $link['label'] }}</a>@endforeach @if($site['show_development'] && $pages->has('page:development'))<a href="{{ route('development') }}">Development</a>@endif @if($pages->has('page:contact') && $page->key !== 'page:contact')<a class="footer-contact" href="{{ route('contact') }}">{{ $footerCta ? $site['footer_heading'] : 'Contact' }} <x-site-icon name="arrow-up-right" /></a>@endif</div></div>
        <div class="container footer-bottom"><span>© {{ now('Europe/Amsterdam')->year }} {{ $profile['name'] }}</span><span class="footer-time"><span class="status-dot"></span> Nederlandse tijd <time data-local-time>{{ now('Europe/Amsterdam')->format('H:i') }}</time></span>@if($pages->has('page:privacy'))<a href="{{ route('privacy') }}">Privacy</a>@endif</div>
    </footer><div class="toast" role="status" aria-live="polite" data-toast></div>
</body></html>
