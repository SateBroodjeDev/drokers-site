@props(['id' => 'personal-world'])
<section class="personal-world" data-personal-world data-world="circuit" aria-labelledby="{{ $id }}-heading">
    <div class="container personal-world__grid">
        <div class="personal-world__story" data-reveal>
            <p class="eyebrow">Buiten mijn werk</p>
            <h2 id="{{ $id }}-heading"><x-heading-lines :text="$site['world_heading']" /></h2>
            <p class="personal-world__intro">{{ $site['world_intro'] }}</p>
            <div class="world-switch" role="group" aria-label="Ontdek mijn interesses en karakter" data-world-controls hidden>
                <button type="button" data-world-select="circuit" aria-pressed="true" aria-controls="{{ $id }}-circuit"><span class="checkered-mark" aria-hidden="true"></span> Circuit</button>
                <button type="button" data-world-select="code" aria-pressed="false" aria-controls="{{ $id }}-code"><x-site-icon name="code" /> Code</button>
                <button type="button" data-world-select="character" aria-pressed="false" aria-controls="{{ $id }}-character"><x-site-icon name="spark" /> Karakter</button>
            </div>
            <div class="world-panels" aria-live="polite" aria-atomic="true">@foreach($worlds as $world)@php($mode = str_replace('world:', '', $world->key))<div class="world-panel" id="{{ $id }}-{{ $mode }}" data-world-panel="{{ $mode }}"><h3>{{ $world->title }}</h3><p>{{ $world->summary }}</p>@if($worldImages->get($world->id))<img class="world-panel-image" src="{{ $worldImages->get($world->id)['url'] }}" alt="{{ $worldImages->get($world->id)['alt'] }}" loading="lazy">@endif<span class="world-note">{{ $world->data['note'] ?? '' }}</span></div>@endforeach</div>
            @if($page->key !== 'page:about' && $pages->has('page:about'))<a class="text-link" href="{{ route('about') }}">Iets meer over mij <x-site-icon name="arrow-right" /></a>@elseif($pages->has('page:projects'))<a class="text-link" href="{{ route('projects') }}">Mijn projecten ontdekken <x-site-icon name="arrow-right" /></a>@endif
        </div>
        <div class="circuit-board" data-reveal>
            <div class="circuit-board__top"><span class="board-label"><i></i> {{ $profile['name'] }} / buiten de kaders</span><span class="checkered-mark" aria-hidden="true"></span></div>
            <div class="circuit-stage">
                <div class="circuit-coordinate" aria-hidden="true"><span>PASSIE / 01</span><span>VAN APEX<br>NAAR IDEE.</span></div>
                <svg class="circuit-map" viewBox="0 0 520 350" aria-hidden="true">
                    <defs><path id="{{ $id }}-track" pathLength="1" d="M 126 252 C 87 252 68 219 84 186 L 118 115 C 133 83 164 77 195 94 L 252 125 C 270 135 286 131 295 113 L 320 63 C 329 44 356 42 371 57 L 427 111 C 449 132 448 160 428 182 L 348 271 C 328 294 307 298 277 283 L 230 260 C 207 249 188 252 166 252 Z" data-circuit-path /></defs>
                    <use href="#{{ $id }}-track" class="track-shadow" />
                    <use href="#{{ $id }}-track" class="track-kerb" />
                    <use href="#{{ $id }}-track" class="track-road" />
                    <use href="#{{ $id }}-track" class="track-line" />
                    <use href="#{{ $id }}-track" class="track-progress" pathLength="1" data-circuit-progress />
                    <path class="track-grid" d="M125 239v26m5-26v26m5-26v26" />
                    <g class="track-car" data-circuit-car transform="translate(126 252) rotate(180)"><ellipse cx="0" cy="2" rx="13" ry="7" fill="#000" opacity=".2"/><rect x="-11" y="-6" width="22" height="12" rx="5" fill="#efb067"/><rect x="-4" y="-4" width="8" height="8" rx="2" fill="#1e2b3d"/><path d="M7-5V5" stroke="#fff4d7" stroke-width="2"/></g>
                    <text class="track-sector" x="183" y="205">INITIATIEF</text><text class="track-sector" x="356" y="207">FOCUS</text><text class="track-sector" x="239" y="76">PLEZIER</text>
                </svg>
                <div class="world-code-art" aria-hidden="true"><div><span>● ● ●</span><span>de-dingen-die-mij-drijven.js</span></div><p><span>const</span> domenic = {</p><p>&nbsp; interesses: [<em>'autosport'</em>, <em>'tech'</em>],</p><p>&nbsp; aanpak: <em>'gewoon beginnen'</em>,</p><p>&nbsp; stilzitten: <strong>false</strong></p><p>};<b>▌</b></p><small>// Er is altijd een volgend idee.</small></div>
                <div class="world-character-art" aria-hidden="true">@foreach(array_slice($site['traits'], 0, 3) as $trait)<span>{{ $trait }} <x-site-icon name="spark" /></span>@endforeach<i>+ een beetje humor.</i></div>
                <span class="circuit-map-caption">Vrije circuitillustratie · geen circuitkaart</span>
            </div>
            <div class="circuit-board__bottom"><div><span class="board-label">Een beetje actie?</span><p role="status" aria-live="polite" data-circuit-status>De start is aan jou.</p></div><button class="lap-button" type="button" aria-pressed="false" data-circuit-lap hidden><span data-lap-label>Rijd een rondje</span><x-site-icon name="arrow-right" /></button></div>
        </div>
    </div>
</section>
