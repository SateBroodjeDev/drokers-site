@props(['entry'])
<details @class(['resume-row', 'resume-row--current' => $entry['current']]) name="resume-role">
    <summary>
        @if($entry['logo'] ?? null)<img @class(['organization-logo', 'organization-mark--dark' => $entry['logo']['background'] === 'dark']) src="{{ $entry['logo']['url'] }}" alt="" loading="lazy">@else<span class="resume-row__mark" aria-hidden="true"><x-site-icon name="{{ $entry['current'] ? 'spark' : 'arrow-right' }}" /></span>@endif
        <span class="resume-row__identity"><strong>{{ $entry['role'] }}</strong><span>{{ $entry['organization'] }}@if($entry['current'])<span class="resume-row__current">Huidig</span>@endif</span></span>
        <span class="resume-row__period">{{ $entry['period'] }}</span>
        <x-site-icon name="arrow-down" />
    </summary>
    <div class="resume-row__body">
        @if($entry['location'] || $entry['arrangement'])<p class="resume-row__context">{{ implode(' · ', array_filter([$entry['location'], $entry['arrangement']])) }}</p>@endif
        @if($entry['summary'])<p>{{ $entry['summary'] }}</p>@endif
        @if($entry['responsibilities'])<ul>@foreach($entry['responsibilities'] as $responsibility)<li>{{ $responsibility }}</li>@endforeach</ul>@endif
    </div>
</details>
