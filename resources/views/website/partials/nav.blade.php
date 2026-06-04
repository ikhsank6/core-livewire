{{-- ============================================================
     Navigation — Aveit-style: transparent top, sticky glass on scroll
     ============================================================ --}}
<nav aria-label="Navigasi utama"
     x-data="{ mobileMenuOpen: false, scrolled: false }"
     @scroll.window="scrolled = window.pageYOffset > 60"
     class="z-50 transition-all duration-300"
     :class="scrolled ? 'fixed top-0 left-0 right-0' : 'absolute top-0 left-0 right-0'">

    {{-- ── Navbar bar ── --}}
    <div class="transition-all duration-300"
         :class="scrolled
             ? 'bg-white/90 dark:bg-dark/90 backdrop-blur-xl shadow-lg shadow-black/5 border-b border-slate-100/60 dark:border-dark-border/60'
             : 'bg-transparent'">

        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex items-center justify-between"
                 :class="scrolled ? 'h-16' : 'h-20'">

                {{-- ── Logo ── --}}
                <a href="/" class="shrink-0 group" aria-label="Halaman beranda">
                    @include('website.partials.logo', [
                        'logoClass'       => 'h-8 transition-all duration-200 group-hover:scale-105',
                        'textClass'       => 'font-heading font-bold text-base transition-colors duration-200',
                        'alpineTextClass' => "{ 'text-slate-900 dark:text-white': scrolled, 'text-white drop-shadow-md': !scrolled }",
                    ])
                </a>

                {{-- ── Desktop nav (center) ── --}}
                <div class="hidden lg:flex items-center gap-1">
                    @php
                        $navItems = [
                            ['url' => '/',                         'label' => 'Beranda', 'active' => request()->is('/')],
                            ['url' => route('news.index'),         'label' => 'Berita',  'active' => request()->is('news*')],
                            ['url' => route('about'),              'label' => 'Tentang', 'active' => request()->is('about*')],
                            ['url' => route('about') . '#contact', 'label' => 'Kontak',  'active' => false],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                    <a href="{{ $item['url'] }}"
                       class="nav-link {{ $item['active'] ? 'is-active' : '' }} relative px-4 py-2.5 text-sm font-semibold rounded-xl font-heading overflow-hidden"
                       :class="scrolled
                           ? '{{ $item['active']
                               ? 'text-primary dark:text-primary-light'
                               : 'text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary' }}'
                           : '{{ $item['active'] ? 'text-white' : 'text-white/85 hover:text-white' }}'">

                        {{-- Hover / active pill background --}}
                        <span class="nav-pill absolute inset-0 rounded-xl {{ $item['active'] ? 'is-active' : '' }}"
                              :class="scrolled
                                  ? '{{ $item['active'] ? 'bg-primary/10 dark:bg-primary/18' : 'bg-slate-100/90 dark:bg-white/6' }}'
                                  : '{{ $item['active'] ? 'bg-white/18 backdrop-blur-sm' : 'bg-white/12' }}'">
                        </span>

                        {{-- Shimmer on hover --}}
                        <span class="nav-shimmer"></span>

                        {{-- Label text --}}
                        <span class="nav-text relative z-10">{{ $item['label'] }}</span>

                        {{-- Active bar: animated underline --}}
                        @if($item['active'])
                        <span class="nav-active-bar absolute bottom-0 left-1/2 w-3/5 h-0.5 rounded-full -translate-x-1/2"
                              :class="scrolled ? 'bg-primary' : 'bg-white/90'">
                        </span>
                        @endif
                    </a>
                    @endforeach
                </div>

                {{-- ── Desktop: CTA + Theme toggle ── --}}
                <div class="hidden lg:flex items-center gap-3">
                    {{-- Dark mode toggle --}}
                    <button @click="theme = isDark ? 'light' : 'dark'"
                            aria-label="Ganti tema"
                            class="p-2 rounded-xl transition-all duration-200 hover:scale-110 active:scale-95"
                            :class="scrolled
                                ? 'text-slate-400 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-600 dark:hover:text-white'
                                : 'text-white/70 hover:text-white hover:bg-white/10'">
                        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    {{-- Auth CTA --}}
                    @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm text-white rounded-full transition-all duration-200 hover:scale-[1.03] active:scale-[0.97] font-heading font-bold"
                       :class="scrolled
                           ? 'bg-primary hover:bg-primary-dark shadow-md shadow-primary/30'
                           : 'bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/20'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('auth.login') }}"
                       class="text-sm font-semibold transition-all duration-200 font-heading"
                       :class="scrolled
                           ? 'text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary'
                           : 'text-white/85 hover:text-white'">
                        Masuk
                    </a>
                    <a href="{{ route('auth.register') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-primary hover:bg-primary-dark rounded-full shadow-lg shadow-primary/25 transition-all duration-200 hover:scale-[1.03] active:scale-[0.97] font-heading">
                        Daftar
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    @endauth
                </div>

                {{-- ── Mobile controls ── --}}
                <div class="flex items-center gap-1.5 lg:hidden">
                    <button @click="theme = isDark ? 'light' : 'dark'"
                            aria-label="Ganti tema"
                            class="p-2 rounded-xl transition-all duration-200"
                            :class="scrolled ? 'text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10' : 'text-white/80 hover:bg-white/10'">
                        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            :aria-expanded="mobileMenuOpen"
                            aria-controls="mobile-menu"
                            aria-label="Buka / tutup menu"
                            class="p-2 rounded-xl transition-all duration-200"
                            :class="scrolled ? 'hover:bg-slate-100 dark:hover:bg-white/10' : 'hover:bg-white/10'">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6"
                             :class="scrolled ? 'text-slate-700 dark:text-slate-300' : 'text-white'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6"
                             :class="scrolled ? 'text-slate-700 dark:text-slate-300' : 'text-white'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Mobile menu ── --}}
    <div id="mobile-menu"
         x-show="mobileMenuOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="lg:hidden mx-3 mt-1.5 py-4 bg-white/95 dark:bg-dark-card/95 backdrop-blur-2xl rounded-2xl shadow-2xl shadow-black/15 border border-slate-100 dark:border-dark-border">

        <div class="flex flex-col gap-0.5 px-3 mb-3">
            @foreach($navItems as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen = false"
               class="relative flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 font-heading overflow-hidden
                      {{ $item['active']
                          ? 'text-primary bg-primary/8 dark:bg-primary/15'
                          : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-primary' }}">
                {{-- Active left bar --}}
                @if($item['active'])
                    <span class="shrink-0 w-1 h-5 bg-primary rounded-full"
                          style="animation: nav-bar-in 0.4s cubic-bezier(0.34,1.56,0.64,1) both; transform-origin: top; animation-name: nav-mobile-bar;">
                    </span>
                @else
                    <span class="shrink-0 w-1 h-5 rounded-full opacity-0"></span>
                @endif
                {{ $item['label'] }}

                {{-- Active dot on right --}}
                @if($item['active'])
                    <span class="ml-auto w-1.5 h-1.5 bg-primary rounded-full shrink-0"></span>
                @endif
            </a>
            @endforeach
        </div>

        <div class="border-t border-slate-100 dark:border-dark-border mx-3 pt-3 px-0 space-y-2">
            @auth
            <a href="{{ route('dashboard') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-primary hover:bg-primary-dark rounded-xl shadow-md shadow-primary/25 transition-all font-heading">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>
            @else
            <a href="{{ route('auth.login') }}"
               class="block w-full text-center px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-white/5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/10 transition-colors font-heading">
                Masuk
            </a>
            <a href="{{ route('auth.register') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-primary hover:bg-primary-dark rounded-xl shadow-md shadow-primary/20 transition-all font-heading">
                Daftar Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            @endauth
        </div>
    </div>
</nav>
