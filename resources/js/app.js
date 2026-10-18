const menuButton = document.querySelector('[data-menu-toggle]');
const navigation = document.querySelector('#site-nav');

const appearanceButton = document.querySelector('[data-appearance-toggle]');
const appearance = window.portfolioAppearance;
if (appearanceButton && appearance) {
    const updateAppearanceButton = () => {
        const isNight = appearance.current() === 'night';
        const label = isNight ? 'Lichte weergave inschakelen' : 'Donkere weergave inschakelen';
        appearanceButton.setAttribute('aria-label', label);
        appearanceButton.setAttribute('title', label);
        appearanceButton.setAttribute('aria-pressed', String(isNight));
        appearanceButton.querySelector('[data-appearance-label]').textContent = label;
        appearanceButton.querySelector('[data-appearance-icon="light"]').hidden = !isNight;
        appearanceButton.querySelector('[data-appearance-icon="night"]').hidden = isNight;
    };
    appearanceButton.addEventListener('click', () => appearance.set(appearance.current() === 'night' ? 'light' : 'night', true));
    window.addEventListener('portfolio-appearance-change', updateAppearanceButton);
    window.addEventListener('storage', (event) => {
        if (event.key === appearance.storageKey && !appearance.preview) appearance.set(event.newValue);
    });
    updateAppearanceButton();
    appearanceButton.hidden = false;
}

const closeMenu = () => {
    if (!menuButton || !navigation) return;
    menuButton.setAttribute('aria-expanded', 'false');
    navigation.classList.remove('is-open');
    menuButton.querySelector('[data-menu-label]').textContent = 'Menu';
};

menuButton?.addEventListener('click', () => {
    const open = menuButton.getAttribute('aria-expanded') !== 'true';
    menuButton.setAttribute('aria-expanded', String(open));
    navigation.classList.toggle('is-open', open);
    menuButton.querySelector('[data-menu-label]').textContent = open ? 'Sluiten' : 'Menu';
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && menuButton?.getAttribute('aria-expanded') === 'true') {
        closeMenu();
        menuButton.focus();
    }
});
document.addEventListener('click', (event) => {
    if (!event.target.closest('.site-header')) closeMenu();
});
navigation?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
window.matchMedia('(min-width: 1000px)').addEventListener('change', closeMenu);

const toast = document.querySelector('[data-toast]');
let toastTimer;
const notify = (message) => {
    if (!toast) return;
    window.clearTimeout(toastTimer);
    toast.textContent = message;
    toast.classList.add('is-visible');
    toastTimer = window.setTimeout(() => toast.classList.remove('is-visible'), 4000);
};

document.querySelectorAll('[data-copy]').forEach((button) => {
    if (button.matches('[data-contact-share]')) button.hidden = false;
    button.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(button.dataset.copy);
            notify('Websitelink gekopieerd. Tot de volgende keer!');
        } catch {
            const fallback = document.querySelector('[data-copy-fallback]');
            if (fallback) fallback.hidden = false;
            notify('Automatisch kopiëren lukt hier niet. De link staat onder de knop.');
        }
    });
});

document.querySelectorAll('[data-print]').forEach((button) => {
    button.addEventListener('click', () => window.print());
});

const clock = document.querySelector('[data-local-time]');
const updateClock = () => {
    if (!clock) return;
    clock.textContent = new Intl.DateTimeFormat('nl-NL', {
        timeZone: 'Europe/Amsterdam', hour: '2-digit', minute: '2-digit',
    }).format(new Date());
};
updateClock();
if (clock) window.setInterval(updateClock, 60000);

document.querySelectorAll('[data-activity]').forEach((activity) => {
    activity.querySelectorAll('[data-period]').forEach((button) => {
        button.addEventListener('click', () => {
            const weeks = Number(button.dataset.period);
            activity.querySelectorAll('[data-period]').forEach((option) => {
                const selected = option === button;
                option.setAttribute('aria-pressed', String(selected));
                option.classList.toggle('is-active', selected);
            });
            activity.querySelector('[data-calendar]').style.setProperty('--weeks', weeks);
            activity.querySelectorAll('[data-calendar-week]').forEach((week) => {
                week.hidden = Number(week.dataset.calendarWeek) < 52 - weeks;
            });
            const labels = activity.querySelector('.calendar-labels');
            labels.innerHTML = (weeks === 13 ? ['Okt', 'Nov', 'Dec'] : ['Jan', 'Apr', 'Jul', 'Okt', 'Dec'])
                .map((label) => `<span>${label}</span>`).join('');
        });
    });
});

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const motionDisabled = () => reducedMotion.matches || document.body.dataset.motion === 'off';
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');

// Content stays visible when JavaScript or motion effects are unavailable.
if ('IntersectionObserver' in window && !motionDisabled()) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('[data-reveal]').forEach((element) => observer.observe(element));
}

// A little depth follows a mouse; touch and reduced-motion visitors get a still image.
document.querySelectorAll('[data-pointer-depth], .hero-copy .button, .development-teaser .button').forEach((element) => {
    const depth = element.hasAttribute('data-pointer-depth');
    const properties = depth ? ['--depth-x', '--depth-y'] : ['--magnet-x', '--magnet-y'];
    let frame;
    const reset = () => {
        window.cancelAnimationFrame(frame);
        properties.forEach((property) => element.style.removeProperty(property));
    };
    element.addEventListener('pointermove', (event) => {
        if (motionDisabled() || !finePointer.matches || event.pointerType !== 'mouse') return;
        const bounds = element.getBoundingClientRect();
        const x = (event.clientX - bounds.left) / bounds.width - 0.5;
        const y = (event.clientY - bounds.top) / bounds.height - 0.5;
        window.cancelAnimationFrame(frame);
        frame = window.requestAnimationFrame(() => {
            element.style.setProperty(properties[0], `${(x * (depth ? 16 : 7)).toFixed(2)}px`);
            element.style.setProperty(properties[1], `${(y * (depth ? 16 : 7)).toFixed(2)}px`);
        });
    }, { passive: true });
    element.addEventListener('pointerleave', reset);
    element.addEventListener('blur', reset);
    reducedMotion.addEventListener('change', reset);
    finePointer.addEventListener('change', reset);
});

const readingProgress = document.querySelector('[data-reading-progress]');
if (readingProgress) {
    let pending = false;
    const updateProgress = () => {
        const available = document.documentElement.scrollHeight - window.innerHeight;
        const progress = available > 0 ? Math.min(1, Math.max(0, window.scrollY / available)) : 0;
        readingProgress.style.transform = `scaleX(${progress})`;
        pending = false;
    };
    const scheduleProgress = () => {
        if (pending) return;
        pending = true;
        window.requestAnimationFrame(updateProgress);
    };
    document.addEventListener('scroll', scheduleProgress, { passive: true });
    window.addEventListener('resize', scheduleProgress, { passive: true });
    updateProgress();
}

document.querySelectorAll('[data-personal-world]').forEach((world) => {
    const controls = world.querySelector('[data-world-controls]');
    const panels = world.querySelectorAll('[data-world-panel]');
    const buttons = world.querySelectorAll('[data-world-select]');
    const path = world.querySelector('[data-circuit-path]');
    const car = world.querySelector('[data-circuit-car]');
    const lapButton = world.querySelector('[data-circuit-lap]');
    const lapLabel = world.querySelector('[data-lap-label]');
    const status = world.querySelector('[data-circuit-status]');
    const trackLength = path.getTotalLength();
    const duration = 4800;
    let elapsed = 0;
    let previousTime = null;
    let frame;
    let running = false;

    const positionCar = (progress) => {
        const distance = Math.min(progress, 0.9999) * trackLength;
        const point = path.getPointAtLength(distance);
        const next = path.getPointAtLength((distance + 2) % trackLength);
        const angle = Math.atan2(next.y - point.y, next.x - point.x) * 180 / Math.PI;
        car.setAttribute('transform', `translate(${point.x} ${point.y}) rotate(${angle})`);
        world.style.setProperty('--lap-remaining', String(1 - progress));
    };
    const pause = () => {
        if (!running) return;
        running = false;
        window.cancelAnimationFrame(frame);
        previousTime = null;
        lapLabel.textContent = 'Vervolg rondje';
        status.textContent = 'Even een pitstop.';
        lapButton.setAttribute('aria-pressed', 'false');
    };
    const finish = () => {
        running = false;
        elapsed = duration;
        window.cancelAnimationFrame(frame);
        previousTime = null;
        positionCar(1);
        lapLabel.textContent = 'Nog een rondje?';
        status.textContent = 'Finish. Op naar het volgende idee.';
        lapButton.setAttribute('aria-pressed', 'false');
    };
    const selectWorld = (selected) => {
        if (selected !== 'circuit') pause();
        world.dataset.world = selected;
        buttons.forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.worldSelect === selected)));
        panels.forEach((panel) => { panel.hidden = panel.dataset.worldPanel !== selected; });
    };
    const tick = (time) => {
        if (!running) return;
        if (previousTime !== null) elapsed += time - previousTime;
        previousTime = time;
        positionCar(Math.min(elapsed / duration, 1));
        if (elapsed >= duration) return finish();
        frame = window.requestAnimationFrame(tick);
    };

    buttons.forEach((button) => button.addEventListener('click', () => selectWorld(button.dataset.worldSelect)));
    lapButton.addEventListener('click', () => {
        if (running) return pause();
        selectWorld('circuit');
        // The action remains available without moving the car across the screen.
        if (motionDisabled()) return finish();
        if (elapsed >= duration) elapsed = 0;
        running = true;
        previousTime = null;
        lapLabel.textContent = 'Pauzeer rondje';
        lapButton.setAttribute('aria-pressed', 'true');
        status.textContent = 'Onderweg. Met aandacht voor elke bocht.';
        frame = window.requestAnimationFrame(tick);
    });
    document.addEventListener('visibilitychange', () => { if (document.hidden) pause(); });
    reducedMotion.addEventListener('change', () => { if (motionDisabled() && running) finish(); });
    if ('IntersectionObserver' in window) {
        const visibility = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting) pause();
        });
        visibility.observe(world.querySelector('.circuit-board'));
    }
    buttons.forEach((button) => { button.hidden = ![...panels].some((panel) => panel.dataset.worldPanel === button.dataset.worldSelect); });
    const requestedWorld = new URLSearchParams(window.location.search).get('interesse');
    const initialWorld = [...panels].some(panel => panel.dataset.worldPanel === requestedWorld)
        ? requestedWorld : (panels[0]?.dataset.worldPanel ?? 'circuit');
    selectWorld(initialWorld);
    positionCar(0);
    controls.hidden = false;
    lapButton.hidden = ![...panels].some((panel) => panel.dataset.worldPanel === 'circuit');
    world.classList.add('is-enhanced');
});

document.querySelectorAll('[data-project-gallery]').forEach((gallery) => {
    const links = [...gallery.querySelectorAll('[data-gallery-image]')];
    const dialog = gallery.querySelector('[data-gallery-dialog]');
    if (!dialog || typeof dialog.showModal !== 'function' || !links.length) return;
    const image = dialog.querySelector('[data-gallery-full-image]');
    const caption = dialog.querySelector('[data-gallery-caption]');
    const counter = dialog.querySelector('[data-gallery-counter]');
    const previous = dialog.querySelector('[data-gallery-previous]');
    const next = dialog.querySelector('[data-gallery-next]');
    let selected = 0;
    let opener = null;
    let bodyOverflow = '';

    const show = (index) => {
        selected = (index + links.length) % links.length;
        const link = links[selected];
        image.src = link.href;
        image.alt = link.querySelector('img').alt;
        caption.textContent = link.dataset.caption;
        counter.textContent = `${selected + 1} / ${links.length}`;
        previous.disabled = next.disabled = links.length < 2;
    };

    links.forEach((link, index) => link.addEventListener('click', (event) => {
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button !== 0) return;
        event.preventDefault();
        opener = link;
        show(index);
        bodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        dialog.showModal();
    }));
    previous.addEventListener('click', () => show(selected - 1));
    next.addEventListener('click', () => show(selected + 1));
    dialog.querySelector('[data-gallery-close]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
            event.preventDefault();
            show(selected + (event.key === 'ArrowRight' ? 1 : -1));
        }
    });
    dialog.addEventListener('close', () => {
        document.body.style.overflow = bodyOverflow;
        opener?.focus({ preventScroll: true });
    });
});
