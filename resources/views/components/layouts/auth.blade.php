<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.fouc-prevention')
    @include('partials.meta-base')

    <title>{{ $title ?? $aboutUs->company_name ?? config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700;800&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @include('partials.theme-scripts', ['includeSidebarState' => false])
    @include('partials.alpine-cloak')

    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Auth-specific overrides using website font tokens */
        .auth-font-heading { font-family: "Quicksand", sans-serif; }
        .auth-font-body    { font-family: "Nunito", "Inter", sans-serif; }
        body { font-family: "Nunito", "Inter", ui-sans-serif, system-ui, sans-serif; }
        h1,h2,h3,h4 { font-family: "Quicksand", sans-serif; }

        /* Left panel floating elements animation */
        @keyframes auth-float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33%       { transform: translateY(-8px) rotate(1deg); }
            66%       { transform: translateY(4px) rotate(-1deg); }
        }
        .auth-float { animation: auth-float 6s ease-in-out infinite; }
        .auth-float-delay { animation: auth-float 6s ease-in-out 2s infinite; }

        @keyframes auth-pulse-ring {
            0% { transform: scale(1); opacity: 0.5; }
            100% { transform: scale(1.8); opacity: 0; }
        }
        .auth-pulse::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            background: rgba(58,108,244,0.3);
            animation: auth-pulse-ring 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
    </style>
</head>

<body class="min-h-full antialiased auth-font-body" style="background: #f4f6fb;">

    {{-- ============================================================
         SPLIT-SCREEN AUTH LAYOUT
         ============================================================ --}}
    <div class="min-h-screen flex">

        {{-- ── LEFT PANEL: Branding (hidden on mobile) ── --}}
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 relative overflow-hidden flex-col justify-between p-12"
             style="background: linear-gradient(145deg, #0d1117 0%, #161b25 40%, #0a1628 75%, #0f1e3d 100%);">

            {{-- Background shapes --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-[-80px] right-[-80px] w-80 h-80 rounded-full"
                     style="background: rgba(58,108,244,0.15); filter: blur(70px);"></div>
                <div class="absolute bottom-[-60px] left-[-60px] w-72 h-72 rounded-full"
                     style="background: rgba(249,115,22,0.1); filter: blur(80px);"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full"
                     style="background: rgba(58,108,244,0.08); filter: blur(60px);"></div>
                {{-- Grid dots --}}
                <div class="absolute inset-0 opacity-[0.04]"
                     style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>
            </div>

            {{-- Top: Logo & Brand --}}
            <div class="relative z-10">
                <a href="/" class="inline-flex items-center gap-3 group" aria-label="Kembali ke beranda">
                    @if($aboutUs?->logo)
                        <img src="{{ Storage::url($aboutUs->logo) }}" alt="Logo"
                             class="h-9 w-9 rounded-xl object-cover brightness-0 invert">
                    @else
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                             style="background: rgba(58,108,244,0.25); border: 1px solid rgba(58,108,244,0.4);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @endif
                    <span class="auth-font-heading font-bold text-white text-lg">
                        {{ $aboutUs?->company_name ?? config('app.name') }}
                    </span>
                </a>
            </div>

            {{-- Middle: Main copy --}}
            <div class="relative z-10 my-auto">
                {{-- Floating UI mockup --}}
                <div class="mb-10 relative">
                    {{-- Main card --}}
                    <div class="auth-float bg-dark-card rounded-2xl border border-white/8 p-5 shadow-2xl max-w-xs"
                         style="background: rgba(22,27,37,0.9);">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-lg bg-primary/20 flex items-center justify-center">
                                <svg class="w-4 h-4" style="color: #6b92f7;" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="stroke: #6b92f7 !important;" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="auth-font-heading font-bold text-white text-sm">Dashboard CMS</p>
                                <p class="text-slate-400 text-xs">Statistik & Laporan</p>
                            </div>
                        </div>
                        {{-- Mini chart --}}
                        <div class="flex items-end gap-1 h-14 mb-3">
                            @foreach([30, 55, 40, 70, 50, 85, 60, 90, 65, 80] as $h)
                            <div class="flex-1 rounded-sm transition-all"
                                 style="height: {{ $h }}%; background: rgba(58,108,244,{{ $h/100 * 0.6 + 0.2 }});"></div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Total Konten</span>
                            <span class="auth-font-heading font-bold text-green-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                </svg>
                                +24%
                            </span>
                        </div>
                    </div>

                    {{-- Floating notification --}}
                    <div class="auth-float-delay absolute -right-4 top-4 bg-white rounded-xl shadow-xl px-3.5 py-2.5 flex items-center gap-2.5">
                        <div class="relative w-8 h-8 rounded-lg bg-green-500/15 flex items-center justify-center auth-pulse">
                            <svg class="w-4 h-4 text-green-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="auth-font-heading font-bold text-slate-800 text-xs">Terautentikasi</p>
                            <p class="text-slate-400 text-[10px]">Sesi aman aktif</p>
                        </div>
                    </div>
                </div>

                {{-- Headline --}}
                <h2 class="auth-font-heading font-extrabold text-white text-3xl xl:text-4xl leading-tight mb-4">
                    Kelola Konten,<br>
                    <span style="color: #6b92f7;">Lebih Mudah &amp;</span><br>
                    Lebih Cepat
                </h2>
                <p class="text-slate-400 text-base leading-relaxed mb-8 max-w-sm">
                    Platform CMS modern dengan akses multi-peran, manajemen berita, dan keamanan berlapis untuk organisasi Anda.
                </p>

                {{-- Feature list --}}
                <ul class="space-y-3">
                    @foreach([
                        ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'text' => 'Akses Multi-Peran & RBAC'],
                        ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'text' => 'Manajemen Berita & Media'],
                        ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'text' => 'Keamanan & Enkripsi Berlapis'],
                    ] as $feature)
                    <li class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                             style="background: rgba(58,108,244,0.2); border: 1px solid rgba(58,108,244,0.3);">
                            <svg class="w-3.5 h-3.5" style="color: #6b92f7;" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="stroke: #6b92f7 !important;" d="{{ $feature['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-slate-300 text-sm">{{ $feature['text'] }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Bottom: Back link spacer --}}
            <div class="relative z-10"></div>
        </div>

        {{-- ── RIGHT PANEL: Form ── --}}
        <div class="flex-1 lg:w-7/12 xl:w-1/2 flex flex-col justify-center px-6 py-12 sm:px-10 md:px-16 lg:px-14 xl:px-20 bg-slate-50 dark:bg-zinc-950 transition-colors duration-200">

            <div class="max-w-md w-full mx-auto">

                {{-- Mobile: Logo & Brand --}}
                <div class="lg:hidden text-center mb-6">
                    @if($aboutUs?->logo)
                        <img src="{{ Storage::url($aboutUs->logo) }}" alt="Logo"
                             class="mx-auto h-12 w-12 rounded-xl object-cover mb-3">
                    @else
                        <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-xl mb-3"
                             style="background: #3a6cf4; box-shadow: 0 4px 12px rgba(58,108,244,0.35);">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @endif
                    <h1 class="auth-font-heading font-bold text-xl text-slate-900 dark:text-white">
                        {{ $aboutUs?->company_name ?? config('app.name', 'Laravel') }}
                    </h1>
                </div>

                {{-- Theme toggle (above card, aligned right) --}}
                <div class="flex justify-end mb-4" x-data="{
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
                        class="flex items-center justify-center w-9 h-9 rounded-xl border border-slate-250 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-750 transition-colors shadow-sm cursor-pointer"
                        title="Toggle theme">
                        <!-- Sun Icon (visible in dark mode) -->
                        <svg x-show="isDark" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="stroke: currentColor !important;" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <!-- Moon Icon (visible in light mode) -->
                        <svg x-show="!isDark" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="stroke: currentColor !important;" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                </div>

                {{-- Form card --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-slate-100 dark:border-zinc-800 px-8 py-8 transition-colors duration-200">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <div class="mt-6 text-center space-y-2">
                    <a href="/" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 dark:text-zinc-500 dark:hover:text-zinc-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="stroke: currentColor !important;" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>


            </div>
        </div>
    </div>

    @include('partials.toast-notifications')

    @fluxScripts
    @filamentScripts
</body>

</html>
