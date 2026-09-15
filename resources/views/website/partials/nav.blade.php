@php
    $navItems = [
        ['label' => 'Beranda', 'href' => route('landing'),            'active' => request()->routeIs('landing')],
        ['label' => 'Layanan', 'href' => route('landing').'#layanan', 'active' => false],
        ['label' => 'Berita',  'href' => route('news.index'),         'active' => request()->routeIs('news.*')],
        ['label' => 'Profil',  'href' => route('about'),              'active' => request()->routeIs('about')],
    ];
@endphp

<header x-data="{ open: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 24"
        @scroll.window="scrolled = window.scrollY > 24"
        @keydown.escape.window="open = false"
        class="fixed inset-x-0 top-0 z-50 transition-colors duration-300"
        :class="scrolled ? 'bg-mineral/85 dark:bg-ink/85 backdrop-blur-md border-b border-hairline/70 dark:border-pine-soft' : 'bg-transparent border-b border-transparent'">

    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="flex items-center justify-between h-18 gap-4">

            {{-- Brand --}}
            <a href="{{ route('landing') }}"
               class="shrink-0 rounded-lg transition-colors"
               :class="scrolled ? 'text-ink dark:text-bone' : 'text-bone'">
                @include('website.partials.logo', [
                    'mark' => 'bone',
                    'textClass' => 'font-display font-bold text-[1.0625rem] tracking-tight',
                ])
            </a>

            {{-- Centre pill group --}}
            <nav aria-label="Navigasi utama"
                 class="hidden lg:flex items-center gap-1 rounded-full p-1.5 border transition-colors duration-300"
                 :class="scrolled
                    ? 'bg-paper dark:bg-ink-card border-hairline dark:border-pine-soft'
                    : 'bg-bone/10 border-bone/15 backdrop-blur-sm'">
                @foreach($navItems as $item)
                    <a href="{{ $item['href'] }}"
                       @if($item['active']) aria-current="page" @endif
                       class="t-micro px-4 py-2.5 rounded-full transition-colors
                              {{ $item['active'] ? 'bg-marigold text-ink' : '' }}"
                       @if(! $item['active'])
                           :class="scrolled
                               ? 'text-moss dark:text-bone-dim hover:text-ink dark:hover:text-bone'
                               : 'text-bone/75 hover:text-bone'"
                       @endif>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- Theme toggle --}}
                <button type="button"
                        @click="theme = isDark ? 'light' : 'dark'"
                        :aria-label="isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
                        class="w-11 h-11 rounded-full grid place-items-center border transition-colors"
                        :class="scrolled
                            ? 'border-hairline dark:border-pine-soft text-moss dark:text-bone-dim hover:text-ink dark:hover:text-bone'
                            : 'border-bone/15 bg-bone/10 text-bone/80 hover:text-bone'">
                    <svg x-show="!isDark" x-cloak class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"
                              stroke-linecap="round"/>
                    </svg>
                    <svg x-show="isDark" x-cloak class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" stroke-linejoin="round"/>
                    </svg>
                </button>

                {{-- Masuk --}}
                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex items-center gap-2 h-11 pl-5 pr-4 rounded-full
                          bg-marigold text-ink hover:bg-marigold-dim transition-colors t-micro">
                    Masuk
                    <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                {{-- Mobile toggle --}}
                <button type="button" @click="open = !open"
                        :aria-expanded="open ? 'true' : 'false'"
                        aria-controls="nav-mobile"
                        aria-label="Buka menu navigasi"
                        class="lg:hidden w-11 h-11 rounded-full grid place-items-center border transition-colors"
                        :class="scrolled
                            ? 'border-hairline dark:border-pine-soft text-ink dark:text-bone'
                            : 'border-bone/15 bg-bone/10 text-bone'">
                    <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path x-show="!open" d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/>
                        <path x-show="open" x-cloak d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile panel --}}
    <div id="nav-mobile" x-show="open" x-cloak x-collapse
         class="lg:hidden border-t border-hairline dark:border-pine-soft bg-mineral dark:bg-ink">
        <nav aria-label="Navigasi utama (seluler)" class="mx-auto max-w-300 px-5 py-4 flex flex-col">
            @foreach($navItems as $item)
                <a href="{{ $item['href'] }}"
                   @if($item['active']) aria-current="page" @endif
                   class="t-micro py-4 border-b border-hairline dark:border-pine-soft last:border-0
                          {{ $item['active'] ? 'text-marigold' : 'text-moss dark:text-bone-dim' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <a href="{{ route('login') }}"
               class="sm:hidden mt-4 inline-flex items-center justify-center gap-2 h-12 rounded-full
                      bg-marigold text-ink t-micro">
                Masuk
            </a>
        </nav>
    </div>
</header>
