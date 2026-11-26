{{--
    Floating "back to top" button, on every public page. It appears once the page has been scrolled.
    Smooth scrolling and the fade follow website.css, which turns both off for reduced motion.
    Focus moves back to the content, because the button disappears when the top is reached.
--}}
<button type="button" x-cloak
        x-data="{ shown: false }"
        x-init="shown = window.scrollY > 80"
        @scroll.window.passive="shown = window.scrollY > 80"
        @click="window.scrollTo({ top: 0 }); document.getElementById('main').focus({ preventScroll: true })"
        x-show="shown"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-3"
        class="to-top print:hidden" aria-label="Kembali ke atas" title="Kembali ke atas">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19V5m0 0l-6 6m6-6l6 6"/>
    </svg>
</button>
