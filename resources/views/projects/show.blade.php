<x-site-layout :title="$project['name']" :description="$project['summary']">
    <section class="container project-heading">
        <a class="back-link" href="{{ route('projects') }}"><x-site-icon name="arrow-left" /> Terug naar projecten</a>
        <div class="project-heading__meta"><span>{{ $project['type'] }}</span><span class="project-status"><span class="status-dot"></span> {{ $project['status'] }}</span></div>
        <div class="page-heading__row"><h1>{{ $project['headline'] ?: $project['name'] }}@if($project['subheadline'])<br><em>{{ $project['subheadline'] }}</em>@endif</h1><p>{{ $project['summary'] }}</p></div>
        @if($project['gallery_images'])<a class="text-link project-image-jump" href="#projectbeelden">Bekijk het project in beeld <x-site-icon name="arrow-down" /></a>@endif
    </section>
    <section class="container">@if($project['cover'])<figure><img class="project-cover" src="{{ $project['cover']['url'] }}" width="{{ $project['cover']['width'] }}" height="{{ $project['cover']['height'] }}" alt="{{ $project['cover']['alt'] }}">@if($project['cover']['caption'])<figcaption class="project-cover-caption">{{ $project['cover']['caption'] }}</figcaption>@endif</figure>@else<x-project-art :kind="$project['visual']" class="project-art--large" />@endif</section>
    <section class="container case-study section">
        <aside class="project-facts">
            <p class="eyebrow">In een oogopslag</p>
            <dl><div><dt>Project</dt><dd>{{ $project['domain'] ?: $project['name'] }}</dd></div><div><dt>Status</dt><dd>{{ $project['status'] }}</dd></div>@if($project['role'])<div><dt>Mijn rol</dt><dd>{{ $project['role'] }}</dd></div>@endif @if($project['started'])<div><dt>Gestart</dt><dd>{{ $project['started'] }}</dd></div>@endif @if($project['technologies'])<div><dt>Techniek</dt><dd>{{ implode(', ', $project['technologies']) }}</dd></div>@endif</dl>
            <div class="project-external-links">@foreach(['url' => 'Naar de website', 'repository_url' => 'Bekijk de code'] as $field => $label)@if($project[$field] && preg_match('~^https?://~i', $project[$field]))<a class="button button--outline" href="{{ $project[$field] }}" target="_blank" rel="noopener noreferrer">{{ $label }} <x-site-icon /></a>@endif @endforeach</div>
            @if($project['chapters'])<nav class="case-index" aria-label="In dit project"><p class="eyebrow">Het verhaal</p>@foreach($project['chapters'] as $chapter)<a href="#hoofdstuk-{{ $loop->iteration }}"><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $chapter['title'] ?? '' }}</a>@endforeach</nav>@endif
        </aside>
        <div class="case-study__story">
            @foreach(preg_split('/\R\s*\R/u', $project['description'] ?? '', -1, PREG_SPLIT_NO_EMPTY) as $paragraph)<p @class(['large-copy' => $loop->first])>{{ $paragraph }}</p>@endforeach
            @foreach($project['chapters'] as $chapter)<section class="case-chapter" id="hoofdstuk-{{ $loop->iteration }}"><span aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><div><h2>{{ $chapter['title'] ?? '' }}</h2>@foreach(preg_split('/\R\s*\R/u', $chapter['body'] ?? '', -1, PREG_SPLIT_NO_EMPTY) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div></section>@endforeach
        </div>
    </section>
    @if($project['gallery_images'])
        <section class="container project-images-section" id="projectbeelden" data-project-gallery aria-labelledby="project-images-heading">
            <div class="section-heading"><div><p class="eyebrow">Van dichtbij</p><h2 id="project-images-heading">Het project <em>in beeld.</em></h2></div><p class="section-heading__note">Een kijkje in het project. Open een afbeelding om de details te bekijken.</p></div>
            <div class="project-gallery">@foreach($project['gallery_images'] as $image)<figure><a class="project-image-link" href="{{ $image['url'] }}" target="_blank" rel="noopener" data-gallery-image data-caption="{{ $image['caption'] ?: $image['alt'] }}" aria-label="Bekijk groter: {{ $image['alt'] }}"><img src="{{ $image['url'] }}" width="{{ $image['width'] }}" height="{{ $image['height'] }}" alt="{{ $image['alt'] }}" loading="lazy"><span class="project-image-link__action" aria-hidden="true">Groot bekijken <x-site-icon /></span></a><figcaption><span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $image['caption'] ?: $image['alt'] }}</figcaption></figure>@endforeach</div>
            <dialog class="project-lightbox" data-gallery-dialog aria-labelledby="project-lightbox-heading">
                <div class="project-lightbox__header"><p id="project-lightbox-heading">{{ $project['name'] }} <span data-gallery-counter role="status"></span></p><button type="button" data-gallery-close>Sluiten <span aria-hidden="true">×</span></button></div>
                <figure><img data-gallery-full-image src="{{ $project['gallery_images'][0]['url'] }}" alt="{{ $project['gallery_images'][0]['alt'] }}"><figcaption data-gallery-caption></figcaption></figure>
                <div class="project-lightbox__controls"><button type="button" data-gallery-previous><x-site-icon name="arrow-left" /> Vorige</button><button type="button" data-gallery-next>Volgende <x-site-icon name="arrow-right" /></button></div>
            </dialog>
        </section>
    @endif
    @if($nextProject)<div class="container next-project"><span>Verder ontdekken</span><a href="{{ route('projects.show', $nextProject['slug']) }}">{{ $nextProject['name'] }} <x-site-icon /></a></div>@endif
</x-site-layout>
