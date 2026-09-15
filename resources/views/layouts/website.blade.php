<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{
    theme: localStorage.getItem('theme') || 'light',
    get isDark() { return this.theme === 'dark'; }
}" x-init="$watch('theme', val => {
    localStorage.setItem('theme', val);
    document.documentElement.classList.toggle('dark', val === 'dark');
})" :class="{ 'dark': isDark }">

<head>
    @include('partials.meta-base')
    <title>@yield('title', $aboutUs->company_name ?? config('app.name'))</title>
    @include('partials.meta-og')
    @include('partials.favicon')

    {{-- Archivo (display, variable wdth axis) · IBM Plex Sans (body) · IBM Plex Mono (data) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@100..125,400..900&family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:wght@400;500;600&display=swap">

    @vite(['resources/css/website.css', 'resources/js/website.js'])

    @include('partials.alpine-cloak')

    @stack('styles')
</head>

<body class="font-sans antialiased bg-mineral dark:bg-ink text-ink dark:text-bone transition-colors duration-300">

    <a href="#main" class="skip-link">Lewati ke konten utama</a>

    @include('website.partials.nav')

    <main id="main">
        @yield('content')
    </main>

    @include('website.partials.footer')

    @stack('scripts')
</body>

</html>
