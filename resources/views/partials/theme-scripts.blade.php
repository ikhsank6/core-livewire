{{-- Alpine Theme Store - for theme state management across Livewire navigations --}}
<style data-navigate-once>
    html.is-navigating *, html.is-navigating *::before, html.is-navigating *::after { transition: none !important; }
</style>
<script data-navigate-once nonce="{{ csp_nonce() }}">
    // Apply theme immediately on every navigation (before Alpine loads)
    (function () {
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.classList.toggle('dark', theme === 'dark');
    })();

    document.addEventListener('alpine:init', () => {
        Alpine.store('theme', {
            value: localStorage.getItem('theme') || 'light',

            init() {
                Alpine.effect(() => {
                    const isDark = this.value === 'dark';
                    document.documentElement.classList.toggle('dark', isDark);
                    localStorage.setItem('theme', this.value);
                });
            },

            get isDark() {
                return this.value === 'dark';
            },

            toggle() {
                this.value = this.isDark ? 'light' : 'dark';
            }
        });

    });

    // wire:navigate (Alpine's navigate plugin) overwrites the <html> class attribute with the
    // incoming page's, which knows nothing about the theme. That drops `dark` for a few
    // milliseconds and the page flashes white before the theme is re-applied. A MutationObserver
    // callback runs as a microtask right after the swap and before the browser paints, so
    // restoring the class here means a light frame is never drawn.
    new MutationObserver(() => {
        const wantsDark = (localStorage.getItem('theme') || 'light') === 'dark';
        const root = document.documentElement;
        if (wantsDark && !root.classList.contains('dark')) {
            // Freeze transitions in the same style change as the restore. The new <body> may already
            // have been styled in its light state, and without the freeze it would fade to dark
            // (app.css gives body, main, a, button, input... a 0.2s colour transition).
            root.classList.add('is-navigating', 'dark');
            requestAnimationFrame(() => requestAnimationFrame(() => root.classList.remove('is-navigating')));
        }
    }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    // Ensure stores exist and sync after Livewire navigation
    document.addEventListener('livewire:navigated', () => {
        const storedTheme = localStorage.getItem('theme') || 'light';

        if (!Alpine.store('theme')) {
            Alpine.store('theme', {
                value: storedTheme,
                get isDark() {
                    return this.value === 'dark';
                },
                toggle() {
                    this.value = this.isDark ? 'light' : 'dark';
                    const isDark = this.value === 'dark';
                    document.documentElement.classList.toggle('dark', isDark);
                    localStorage.setItem('theme', this.value);
                }
            });
        } else {
            // Sync store with localStorage
            if (Alpine.store('theme').value !== storedTheme) {
                Alpine.store('theme').value = storedTheme;
            }
        }


        // Always ensure DOM class is correct
        const isDark = storedTheme === 'dark';
        document.documentElement.classList.toggle('dark', isDark);
    });
</script>