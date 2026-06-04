import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/* ============================================
   AOS (Animate on Scroll) — IntersectionObserver
   NOTE: Vite bundles as type="module" (implicit defer).
   DOM is already ready when this runs — do NOT wrap
   in DOMContentLoaded, that event has already fired.
   ============================================ */
function initAos() {
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
function animateCounter(el) {
    const target = parseInt(el.dataset.target || el.innerText, 10);
    if (isNaN(target)) return;
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
        el.innerText = Math.floor(current).toLocaleString('id-ID');
    }, step);
}

function initCounters() {
    const counterObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 }
    );
    document.querySelectorAll('[data-counter]').forEach((el) => counterObserver.observe(el));
}

// DOM is ready (module scripts are deferred — run directly)
initAos();
initCounters();

// Safety fallback: if observer hasn't fired in 500ms, reveal all
setTimeout(() => {
    document.querySelectorAll('.aos-init:not(.aos-animated)').forEach((el) => {
        el.classList.add('aos-animated');
    });
}, 500);
