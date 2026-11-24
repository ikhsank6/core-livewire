@php
    $navItems = [
        ['label' => 'Beranda', 'href' => route('landing'),             'active' => request()->routeIs('landing')],
        ['label' => 'Skema',   'href' => route('landing').'#skema',    'active' => false],
        ['label' => 'Alur',    'href' => route('landing').'#alur',     'active' => false],
        ['label' => 'Jadwal',  'href' => route('landing').'#jadwal',   'active' => false],
        ['label' => 'Berita',  'href' => route('news.index'),          'active' => request()->routeIs('news.*')],
        ['label' => 'Profil',  'href' => route('about'),               'active' => request()->routeIs('about')],
        ['label' => 'FAQ',     'href' => route('landing').'#faq',      'active' => false],
    ];

    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

{{-- A paper strip with a torn, perforated lower edge. A stamp-shaped frame slides to the item
     you point at and rests on the current page. --}}
<header x-data="{ open: false }"
        @keydown.escape.window="open = false"
        class="torn sticky top-0 z-50 bg-surface text-ink pb-[9px]">

    <div class="wrap">
        <div class="flex items-center h-16 gap-6">

            <a href="{{ route('landing') }}" class="group inline-flex min-h-11 shrink-0 items-center rounded-md" aria-label="{{ $aboutUs->company_name ?? config('app.name') }}, ke beranda">
                @include('website.partials.logo')
            </a>

            <nav aria-label="Navigasi utama"
                 x-data="{
                    x: 0,
                    w: 0,
                    place(el) { this.x = el.offsetLeft; this.w = el.offsetWidth; },
                    rest() {
                        const current = this.$refs.row.querySelector('[aria-current=page]');
                        if (current) { this.place(current); } else { this.w = 0; }
                    }
                 }"
                 x-init="$nextTick(() => rest()); window.addEventListener('resize', () => rest())"
                 @mouseleave="rest()"
                 @focusout="rest()"
                 class="relative hidden lg:block h-full ml-2">
                <span class="nav-stamp" aria-hidden="true"
                      :class="w === 0 ? 'opacity-0' : 'opacity-100'"
                      :style="`transform: translateX(${x}px) rotate(-1.5deg); width: ${w}px`"></span>

                <ul x-ref="row" class="relative flex items-stretch h-full">
                    @foreach($navItems as $item)
                        <li class="h-full">
                            <a href="{{ $item['href'] }}"
                               @if($item['active']) aria-current="page" @endif
                               @mouseenter="place($el)" @focus="place($el)"
                               class="flex items-center h-full px-3.5 text-[0.9375rem] font-semibold transition-colors duration-200
                                      {{ $item['active'] ? 'text-ink' : 'text-body hover:text-ink focus-visible:text-ink' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-2 ml-auto">

                <button type="button"
                        @click="theme = isDark ? 'light' : 'dark'"
                        :aria-label="isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
                        class="w-11 h-11 rounded-md grid place-items-center text-muted hover:text-ink hover:bg-sunken transition-colors cursor-pointer">
                    <span x-show="!isDark">@svg('heroicon-o-sun', 'w-5 h-5')</span>
                    <span x-show="isDark" x-cloak>@svg('heroicon-o-moon', 'w-5 h-5')</span>
                </button>

                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex items-center h-11 px-3 rounded-md text-[0.9375rem] font-semibold text-body hover:text-ink transition-colors">
                    Masuk
                </a>

                <a href="{{ $registerUrl }}" class="btn btn-cap hidden sm:inline-flex">Daftar sebagai asesi</a>

                <button type="button" @click="open = !open"
                        :aria-expanded="open ? 'true' : 'false'"
                        aria-controls="nav-mobile"
                        class="lg:hidden inline-flex items-center gap-2 h-11 pl-3 pr-4 rounded-md border border-line
                               text-[0.9375rem] font-semibold text-ink cursor-pointer">
                    <span x-show="!open">@svg('heroicon-o-bars-3', 'w-5 h-5')</span>
                    <span x-show="open" x-cloak>@svg('heroicon-o-x-mark', 'w-5 h-5')</span>
                    Menu
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile panel: large type, one thumb away --}}
    <div id="nav-mobile" x-show="open" x-cloak x-collapse class="lg:hidden border-t border-line bg-surface">
        <nav aria-label="Navigasi utama (seluler)" class="wrap py-4">
            <ul>
                @foreach($navItems as $item)
                    <li class="border-b border-line last:border-0">
                        <a href="{{ $item['href'] }}" @click="open = false"
                           @if($item['active']) aria-current="page" @endif
                           class="flex min-h-14 items-center justify-between text-2xl font-extrabold tracking-tight
                                  {{ $item['active'] ? 'text-accent-text' : 'text-ink' }}">
                            {{ $item['label'] }}
                            @if($item['active'])
                                <span class="h-2 w-2 rounded-full bg-cap" aria-hidden="true"></span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <a href="{{ route('login') }}" class="btn btn-lg btn-line">Masuk</a>
                <a href="{{ $registerUrl }}" class="btn btn-lg btn-cap">Daftar</a>
            </div>
        </nav>
    </div>
</header>
