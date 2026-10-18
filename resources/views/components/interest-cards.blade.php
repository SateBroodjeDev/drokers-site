<section class="container interest-section" aria-labelledby="interests-heading">
    <div class="interest-section__heading" data-reveal><p class="eyebrow">Waar ik energie van krijg</p><h2 id="interests-heading">{{ $site['interests_heading'] ?? 'Wat mij bezighoudt.' }}</h2>@if($site['world_intro'])<p>{{ $site['world_intro'] }}</p>@endif</div>
    <div class="interest-grid">
        @foreach($worlds as $world)
            @php($mode = str_replace('world:', '', $world->key))
            @php($cardTitle = ($world->data['card_title'] ?? '') ?: match ($mode) { 'circuit' => 'Autosport', 'code' => 'Technologie', 'character' => 'Karakter', default => $world->title })
            <article class="interest-card" data-reveal>
                <span class="interest-card__icon" aria-hidden="true">@if($mode === 'circuit')<span class="checkered-mark"></span>@else<x-site-icon :name="$mode === 'code' ? 'code' : 'spark'" />@endif</span>
                <h3>{{ $cardTitle }}</h3><p>{{ $world->summary }}</p>
                @if($pages->has('page:about'))<a class="text-link" href="{{ route('about', ['interesse' => $mode]) }}#about-world">Lees verder<span class="sr-only">: {{ $world->title }}</span> <x-site-icon name="arrow-right" /></a>@endif
            </article>
        @endforeach
    </div>
</section>
