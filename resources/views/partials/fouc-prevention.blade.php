{{-- Immediate theme application to prevent FOUC (Flash of Unstyled Content) --}}
<script nonce="{{ csp_nonce() }}">
    (function () {
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') document.documentElement.classList.add('dark');
    })();
</script>