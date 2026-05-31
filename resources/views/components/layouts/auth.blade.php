<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.fouc-prevention')
    @include('partials.meta-base')

    <title>{{ $title ?? $aboutUs->company_name ?? config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')
    @include('partials.fonts')
    @include('partials.theme-scripts', ['includeSidebarState' => false])
    @include('partials.alpine-cloak')

    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased bg-gray-50 dark:bg-gray-900">

    {{-- Theme toggle (top right) --}}
    <div class="fixed top-4 right-4 z-50" x-data="{
        get isDark() { return $store.theme ? $store.theme.isDark : (localStorage.getItem('theme') === 'dark') },
        toggle() {
            if ($store.theme) { $store.theme.toggle(); }
            else {
                const t = this.isDark ? 'light' : 'dark';
                localStorage.setItem('theme', t);
                document.documentElement.classList.toggle('dark', t === 'dark');
            }
        }
    }">
        <button @click="toggle()"
            class="flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors shadow-sm">
            <svg x-show="isDark" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg x-show="!isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>
    </div>

    <div class="min-h-full flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">

        {{-- Brand --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md mb-8 text-center">
            @if($aboutUs?->logo)
                <img src="{{ Storage::url($aboutUs->logo) }}" alt="Logo"
                    class="mx-auto h-12 w-12 rounded-xl object-cover">
            @else
                <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 shadow-sm">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            @endif
            <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">
                {{ $aboutUs?->company_name ?? config('app.name', 'Laravel') }}
            </h1>
        </div>

        {{-- Card --}}
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 px-8 py-8">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <p class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
                <a href="/" class="inline-flex items-center gap-1.5 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </p>
        </div>
    </div>

    @include('partials.toast-notifications')

    @fluxScripts
    @filamentScripts
</body>

</html>
