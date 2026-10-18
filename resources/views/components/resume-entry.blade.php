@props(['entry', 'expanded' => false])
<article @class(['resume-entry', 'resume-entry--current' => $entry['current']])>
    <span class="timeline-dot" aria-hidden="true"></span>
    <div class="resume-entry__meta"><p>{{ $entry['period'] }}</p>@if($entry['current'])<span class="resume-current"><span class="status-dot"></span> Huidige rol</span>@endif</div>
    <div class="resume-entry__identity"><h3>{{ $entry['role'] }}</h3>@if($entry['logo'] ?? null)<img @class(['organization-logo', 'organization-mark--dark' => $entry['logo']['background'] === 'dark']) src="{{ $entry['logo']['url'] }}" alt="{{ $entry['organization'] }}" loading="lazy">@endif</div>
    <p class="resume-entry__organization">{{ $entry['organization'] }}@if($entry['location'])<span> / {{ $entry['location'] }}</span>@endif</p>
    @if($entry['arrangement'])<span class="resume-arrangement">{{ $entry['arrangement'] }}</span>@endif
    <p class="resume-entry__summary">{{ $entry['summary'] }}</p>
    @if($entry['responsibilities'])<details class="resume-details" @if($expanded) open @endif>
        <summary>Mijn werkzaamheden <span class="sr-only">als {{ $entry['role'] }} bij {{ $entry['organization'] }}</span><x-site-icon name="arrow-down" /></summary>
        <ul>@foreach($entry['responsibilities'] as $responsibility)<li>{{ $responsibility }}</li>@endforeach</ul>
    </details>@endif
</article>
