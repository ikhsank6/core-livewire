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
    @include('partials.fonts')

    @vite(['resources/css/website.css', 'resources/js/website.js'])

    @include('partials.alpine-cloak')

    @stack('styles')
</head>

<body class="font-sans antialiased bg-white dark:bg-dark text-slate-800 dark:text-slate-200">

    @include('website.partials.nav')

    <main>
        @yield('content')
    </main>

    @include('website.partials.footer')

    @stack('scripts')
</body>

</html>
