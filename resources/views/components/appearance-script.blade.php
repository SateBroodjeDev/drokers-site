{{-- Runs before visible content so a saved preference is applied before first paint. --}}
<script>
(() => {
    const palettes = {{ Illuminate\Support\Js::from($site['palettes']) }};
    const defaultMode = {{ Illuminate\Support\Js::from($site['appearance']) }};
    const preview = {{ Illuminate\Support\Js::from($isPreview) }};
    const storageKey = 'drokers.appearance.v1';
    const apply = (requestedMode, persist = false) => {
        const mode = ['night', 'light'].includes(requestedMode) ? requestedMode : defaultMode;
        document.body.dataset.appearance = mode;
        document.body.style.setProperty('--paper', palettes[mode].background);
        document.body.style.setProperty('--ink', palettes[mode].ink);
        document.documentElement.style.colorScheme = mode === 'night' ? 'dark' : 'light';
        document.querySelector('meta[name="theme-color"]')?.setAttribute('content', palettes[mode].background);
        if (persist && !preview) {
            try { localStorage.setItem(storageKey, mode); } catch {}
        }
        window.dispatchEvent(new CustomEvent('portfolio-appearance-change', { detail: { mode } }));
    };
    window.portfolioAppearance = { set: apply, current: () => document.body.dataset.appearance, storageKey, preview };
    let savedMode = defaultMode;
    if (!preview) {
        try { savedMode = localStorage.getItem(storageKey) || defaultMode; } catch {}
    }
    apply(savedMode);
})();
</script>
