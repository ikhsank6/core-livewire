{{-- Navigation --}}
<nav aria-label="Navigasi utama"
     x-data="{ mobileMenuOpen: false, scrolled: false }"
     @scroll.window="scrolled = window.pageYOffset > 50"
     class="z-50 transition-all duration-300"
     :class="scrolled ? 'fixed top-0 left-0 right-0' : 'absolute top-0 left-0 right-0'">

    {{-- Bar --}}
    <div class="transition-all duration-300"
         :class="scrolled
             ? 'mx-3 mt-3 rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 shadow-lg shadow-black/5'
             : 'bg-transparent'">
        <div class="max-w-7xl mx-auto px-5">
            <div class="flex justify-between items-center"
                 :class="scrolled ? 'h-14' : 'h-18'">

                {{-- Logo --}}
                <a href="/" class="shrink-0 group" aria-label="Halaman beranda">
                    @include('website.partials.logo', [
                        'logoClass'      => 'h-8 transition-transform duration-200 group-hover:scale-105',
                        'textClass'      => 'font-bold text-base transition-colors duration-200',
                        'alpineTextClass' => "{ 'text-slate-900 dark:text-white': scrolled, 'text-white drop-shadow-sm': !scrolled }",
                    ])
                </a>

                {{-- Desktop nav --}}
                <div class="hidden lg:flex items-center gap-2">
                    @php
                        $navItems = [
                            ['url' => '/',                        'label' => 'Beranda', 'active' => request()->is('/')],
                            ['url' => route('news.index'),        'label' => 'Berita',  'active' => request()->is('news*')],
                            ['url' => route('about'),             'label' => 'Tentang', 'active' => request()->is('about*')],
                            ['url' => route('about') . '#contact','label' => 'Kontak',  'active' => false],
                        ];
                    @endphp

                    <div class="flex items-center gap-0.5 p-1 rounded-xl transition-all duration-300"
                         :class="scrolled ? 'bg-slate-100/80 dark:bg-white/5' : 'bg-white/10 backdrop-blur-sm'">
                        @foreach($navItems as $item)
                        <a href="{{ $item['url'] }}"
                           class="relative px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 group
                                  {{ $item['active']
                                      ? 'text-primary dark:text-primary'
                                      : 'hover:text-slate-900 dark:hover:text-white' }}"
                           :class="scrolled
                               ? '{{ $item['active'] ? 'bg-white dark:bg-slate-800 shadow-sm text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800 hover:shadow-sm' }}'
                               : '{{ $item['active'] ? 'text-white bg-white/15' : 'text-white/80 hover:text-white hover:bg-white/10' }}'">
                            {{ $item['label'] }}
                            @if($item['active'])
                            <span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary"></span>
                            @endif
                        </a>
                        @endforeach
                    </div>

                    <div class="w-px h-7 mx-2 transition-colors duration-300"
                         :class="scrolled ? 'bg-slate-200 dark:bg-slate-700' : 'bg-white/20'"></div>

                    {{-- Auth --}}
                    @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-5 py-2 text-sm font-bold text-white bg-accent hover:bg-accent-dark rounded-xl shadow-md shadow-orange-500/25 transition-all duration-200 hover:scale-[1.03] active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('auth.login') }}"
                       class="inline-flex items-center gap-2 px-5 py-2 text-sm font-bold text-white bg-accent hover:bg-accent-dark rounded-xl shadow-md shadow-orange-500/25 transition-all duration-200 hover:scale-[1.03] active:scale-[0.98]">
                        Masuk
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    @endauth

                    {{-- Dark mode toggle --}}
                    <button @click="theme = isDark ? 'light' : 'dark'"
                            aria-label="Ganti tema"
                            class="ml-1 p-2 rounded-xl transition-all duration-200 hover:scale-110 active:scale-95"
                            :class="scrolled
                                ? 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-700 dark:hover:text-white'
                                : 'text-white/70 hover:text-white hover:bg-white/10'">
                        <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                </div>

                {{-- Mobile controls --}}
                <div class="flex items-center gap-1.5 lg:hidden">
                    <button @click="theme = isDark ? 'light' : 'dark'"
                            aria-label="Ganti tema"
                            class="p-2 rounded-xl transition-all duration-200"
                            :class="scrolled ? 'text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10' : 'text-white/80 hover:bg-white/10'">
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

    {{-- Mobile menu --}}
    <div id="mobile-menu"
         x-show="mobileMenuOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="lg:hidden mx-3 mt-1.5 py-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-white/10">
        <div class="flex flex-col gap-0.5 px-3">
            @foreach($navItems as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen = false"
               class="relative px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                      {{ $item['active']
                          ? 'text-primary bg-primary/10 dark:bg-primary/20'
                          : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                @if($item['active'])
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-primary rounded-r-full"></span>
                @endif
                {{ $item['label'] }}
            </a>
            @endforeach

            <div class="border-t border-slate-200/60 dark:border-white/10 mt-2 pt-3 px-1 space-y-2">
                @auth
                <a href="{{ route('dashboard') }}"
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-accent hover:bg-accent-dark rounded-xl shadow-md shadow-orange-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </a>
                @else
                <a href="{{ route('auth.login') }}"
                   class="block w-full text-center px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-200 bg-slate-100/80 dark:bg-white/5 rounded-xl hover:bg-slate-200/80 dark:hover:bg-white/10 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('auth.register') }}"
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 text-sm font-bold text-white bg-accent hover:bg-accent-dark rounded-xl shadow-md shadow-orange-500/20 transition-all">
                    Daftar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
