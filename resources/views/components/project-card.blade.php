@props(['project', 'wide' => false, 'personal' => false])
<article @class(['project-card', 'project-card--wide' => $wide, 'project-card--illustrated' => empty($project['cover'])]) data-reveal>
    <a class="project-card__visual" href="{{ route('projects.show', $project['slug']) }}" aria-label="Bekijk {{ $project['name'] }}">@if($project['cover'] ?? null)<img class="project-cover project-cover--card" src="{{ $project['cover']['url'] }}" width="{{ $project['cover']['width'] }}" height="{{ $project['cover']['height'] }}" alt="{{ $project['cover']['alt'] }}" loading="lazy">@else<x-project-art :kind="$project['visual']" />@endif<span class="project-card__arrow"><x-site-icon /></span></a>
    <div class="project-card__meta"><span>{{ $project['type'] }}</span><span class="project-status"><span class="status-dot"></span> {{ $project['status'] }}</span></div>
    <h3><a href="{{ route('projects.show', $project['slug']) }}">{{ $personal ? $project['name'] : ($project['domain'] ?: $project['name']) }} <x-site-icon /></a></h3>
    <p>{{ $project['summary'] }}</p>
    @if($personal && $project['role'])<p class="person-project-role">{{ $project['role'] }}</p>@endif
    @if(!$personal && $project['technologies'])<div class="tag-list">@foreach($project['technologies'] as $tech)<span class="tag">{{ $tech }}</span>@endforeach</div>@endif
    @if(!$personal && $project['role'] && $project['visual'] === 'racing')<div class="project-founder"><span class="checkered-mark" aria-hidden="true"></span><span>{{ $project['role'] }}@if($project['started']) sinds {{ strtolower($project['started']) }}@endif</span></div>@endif
</article>
