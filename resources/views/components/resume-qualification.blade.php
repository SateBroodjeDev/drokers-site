@props(['qualification'])
<article class="resume-qualification">
    @if($qualification['logo'] ?? null)<img @class(['organization-logo', 'organization-mark--dark' => $qualification['logo']['background'] === 'dark']) src="{{ $qualification['logo']['url'] }}" alt="{{ $qualification['issuer'] }}" loading="lazy">@endif
    <div>@if($qualification['period'])<p class="resume-qualification__period">{{ $qualification['period'] }}</p>@endif<h3>{{ $qualification['title'] }}</h3>@if($qualification['issuer'])<p class="resume-qualification__issuer">{{ $qualification['issuer'] }}</p>@endif @if($qualification['description'] ?? null)<p class="resume-qualification__description">{{ $qualification['description'] }}</p>@endif</div>
    @if($qualification['status'])<span class="qualification-status"><x-site-icon name="check" /> {{ $qualification['status'] }}</span>@endif
</article>
