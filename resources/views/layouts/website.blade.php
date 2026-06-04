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

    {{-- Google Fonts: Quicksand (heading) + Nunito (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700;800&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/website.css', 'resources/js/website.js'])

    @include('partials.alpine-cloak')

    @stack('styles')
</head>

<body class="font-sans antialiased bg-white dark:bg-dark text-slate-800 dark:text-slate-200 transition-colors duration-300">

    @include('website.partials.nav')

    <main>
        @yield('content')
    </main>

    @include('website.partials.footer')

    @stack('scripts')
</body>

</html>
