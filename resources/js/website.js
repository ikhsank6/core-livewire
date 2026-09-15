import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/* Single source of truth for motion preference. The register rail and the
   hero carousel check this too (in CSS and in their Alpine data). */
const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ============================================
   AOS (Animate on Scroll) — IntersectionObserver
   NOTE: Vite bundles as type="module" (implicit defer).
   DOM is already ready when this runs — do NOT wrap
   in DOMContentLoaded, that event has already fired.
   ============================================ */
function initAos() {
    // Reduced motion: reveal everything at once, no observer needed.
    if (prefersReducedMotion()) {
        document.querySelectorAll('.aos-init').forEach((el) => {
            el.classList.add('aos-animated');
        });
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animated');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -30px 0px' }
    );

    document.querySelectorAll('.aos-init').forEach((el) => observer.observe(el));

    // Re-init after Livewire SPA navigation
    document.addEventListener('livewire:navigated', () => {
        document.querySelectorAll('.aos-init:not(.aos-animated)').forEach((el) => {
            observer.observe(el);
        });
    });
}

/* ============================================
   ANIMATED NUMBER COUNTER
   ============================================ */
function setCounter(el, value) {
    el.innerText = Math.floor(value).toLocaleString('id-ID');
}

function animateCounter(el) {
    // Strictly data-target: innerText carries the locale-formatted figure
    // ("1.248"), which parseInt would read as 1.
    const target = parseInt(el.dataset.target, 10);
    if (isNaN(target)) return;

    // Reduced motion: show the final figure, never count up to it.
    if (prefersReducedMotion()) {
        setCounter(el, target);
        return;
    }

    const duration = 1600;
    const step = 16;
    const steps = Math.ceil(duration / step);
    let current = 0;
    const increment = target / steps;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        setCounter(el, current);
    }, step);
}

function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');

    if (prefersReducedMotion()) {
        counters.forEach((el) => animateCounter(el));
        return;
    }

    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        },
        // 0.25 rather than 0.5: on short viewports the stat row's lower half
        // never clears the fold, and the counter would never fire.
        { threshold: 0.25 }
    );

    counters.forEach((el) => counterObserver.observe(el));
}

// DOM is ready (module scripts are deferred — run directly)
initAos();
initCounters();

// Safety fallback: if the observer hasn't fired in 500ms, reveal all
setTimeout(() => {
    document.querySelectorAll('.aos-init:not(.aos-animated)').forEach((el) => {
        el.classList.add('aos-animated');
    });
}, 500);
