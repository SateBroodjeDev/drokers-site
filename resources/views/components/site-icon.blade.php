@props(['name' => 'arrow-up-right'])
{{-- Separate name prevents a collision with Blade UI Icons used by Filament. --}}
<svg {{ $attributes->class(['icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('arrow-right') <path d="M4 12h16M13 5l7 7-7 7"/> @break
        @case('arrow-down') <path d="M12 4v16M5 13l7 7 7-7"/> @break
        @case('arrow-left') <path d="M20 12H4m7 7-7-7 7-7"/> @break
        @case('menu') <path d="M4 8h16M4 16h16"/> @break
        @case('sun') <circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M5 5l1.5 1.5m11 11L19 19M5 19l1.5-1.5m11-11L19 5"/> @break
        @case('moon') <path d="M20.5 14A8.5 8.5 0 0 1 10 3.5 8.5 8.5 0 1 0 20.5 14Z"/> @break
        @case('close') <path d="m6 6 12 12M6 18 18 6"/> @break
        @case('code') <path d="m8 7-5 5 5 5m8-10 5 5-5 5M14 4l-4 16"/> @break
        @case('briefcase') <rect x="3" y="7" width="18" height="14" rx="3"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12a22 22 0 0 0 18 0M10 13h4"/> @break
        @case('education') <path d="m2 9 10-5 10 5-10 5L2 9Zm4 2v6c4 3 8 3 12 0v-6m4-2v7"/> @break
        @case('spark') <path d="m12 2 2.5 7.5L22 12l-7.5 2.5L12 22l-2.5-7.5L2 12l7.5-2.5L12 2Z"/> @break
        @case('search') <circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/> @break
        @case('print') <path d="M7 8V3h10v5M7 17H4a1 1 0 0 1-1-1v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6a1 1 0 0 1-1 1h-3"/><path d="M7 14h10v7H7zM17 11h1"/> @break
        @case('copy') <rect x="8" y="8" width="13" height="13" rx="3"/><path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/> @break
        @case('check') <path d="m5 12 4 4L19 6"/> @break
        @case('globe') <circle cx="12" cy="12" r="9"/><ellipse cx="12" cy="12" rx="4" ry="9"/><path d="M3 12h18M5 6h14M5 18h14"/> @break
        @case('mail') <rect x="3" y="5" width="18" height="14" rx="3"/><path d="m3 7 9 6 9-6"/> @break
        @default <path d="M6 18 18 6M6 6h12v12"/>
    @endswitch
</svg>
