<!DOCTYPE html>
<html lang="id" x-data="{
    theme: localStorage.getItem('theme') || 'light',
    get isDark() { return this.theme === 'dark'; }
}" x-init="$watch('theme', val => {
    localStorage.setItem('theme', val);
    document.documentElement.classList.toggle('dark', val === 'dark');
})" :class="{ 'dark': isDark }">

<head>
    {{-- Apply the saved theme before anything paints, so dark mode never flashes white first --}}
    @include('partials.fouc-prevention')
    @include('partials.meta-base')
    <title>@yield('title', $aboutUs->company_name ?? config('app.name'))</title>
    @include('partials.meta-og')
    @include('partials.favicon')

    {{-- Plus Jakarta Sans is the only typeface of the portal --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">

    @vite(['resources/css/website.css', 'resources/js/website.js'])

    @include('partials.alpine-cloak')

    @stack('styles')
</head>

<body class="font-sans antialiased bg-page text-body transition-colors duration-300">

    <a href="#main" class="skip-link">Lewati ke konten utama</a>

    @include('website.partials.nav')

    <main id="main">
        @yield('content')
    </main>

    @include('website.partials.footer')

    @stack('scripts')
</body>

</html>
