@props(['kind' => 'globe'])
@if($kind === 'globe')
    <div {{ $attributes->class(['project-art', 'project-art--globe']) }} aria-hidden="true">
        <div class="art-topline"><span>G / 01</span><span>Een project in ontwikkeling</span></div>
        <div class="orbital-globe"><span class="orbital-globe__equator"></span><span class="orbital-globe__meridian orbital-globe__meridian--one"></span><span class="orbital-globe__meridian orbital-globe__meridian--two"></span><span class="orbital-globe__latitude orbital-globe__latitude--top"></span><span class="orbital-globe__latitude orbital-globe__latitude--bottom"></span><i></i></div>
        <div class="globe-wordmark">globetrotr<span>↗</span></div>
        <span class="art-caption">Projectvisual / ontwerp</span>
    </div>
@elseif($kind === 'racing')
    <div {{ $attributes->class(['project-art', 'project-art--racing']) }} aria-hidden="true">
        <div class="art-topline"><span>DUR / 03</span><span>Een eigen initiatief</span></div>
        <svg class="racing-lines" viewBox="0 0 600 400" fill="none"><path d="M-90 375 155 130Q205 80 260 135L315 190Q350 225 390 185L665-90"/><path d="M-90 395 155 150Q205 100 260 155L315 210Q350 245 390 205L665-70"/><path d="M-90 415 155 170Q205 120 260 175L315 230Q350 265 390 225L665-50"/><path class="racing-lines__highlight" d="M-90 395 155 150Q205 100 260 155L315 210Q350 245 390 205L665-70"/></svg>
        <div class="racing-art-title"><span class="racing-art-title__label"><span class="checkered-mark"></span> Samen aan de start.</span><strong>Dutch<br><span>United</span>Racing<span class="racing-art-title__dot">.</span></strong><p>SIMRACING / COMMUNITY / FAIR PLAY</p></div>
        <span class="art-caption">Eigen portfolio-illustratie · geen officieel logo</span>
    </div>
@else
    <div {{ $attributes->class(['project-art', 'project-art--portfolio']) }} aria-hidden="true">
        <span class="art-topline"><span>DR / 02</span><span>Deze website</span></span>
        <div class="mini-browser"><div class="mini-browser__bar"><span>● ● ●</span><span>domenicrokers.nl</span><span>↗</span></div><div class="mini-browser__page"><div class="mini-browser__nav"><b>dr.</b><span>Over mij &nbsp; Cv &nbsp; Projecten</span></div><p>Hey, ik ben Domenic.</p><div class="mini-browser__headline">Altijd<br>aan het <em>bouwen.</em></div><div class="mini-browser__button">Ontdek mijn projecten ↗</div><div class="mini-browser__shape">dr.</div></div></div>
        <span class="art-caption">Een eigen plek op het web</span>
    </div>
@endif
