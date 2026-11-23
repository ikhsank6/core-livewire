@php
    $navItems = [
        ['label' => 'Beranda', 'href' => route('landing'),            'active' => request()->routeIs('landing')],
        ['label' => 'Layanan', 'href' => route('landing').'#layanan', 'active' => false],
        ['label' => 'Berita',  'href' => route('news.index'),         'active' => request()->routeIs('news.*')],
        ['label' => 'Profil',  'href' => route('about'),              'active' => request()->routeIs('about')],
        ['label' => 'FAQ',     'href' => route('landing').'#faq',     'active' => false],
    ];

    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');
@endphp

{{-- Papan loket: a dark counter board. A lamp hangs from the top edge and
     lights the item you point at; it rests over the current page. --}}
<header x-data="{ open: false }"
        @keydown.escape.window="open = false"
        class="on-dark sticky top-0 z-50 bg-nila text-white border-b border-white/10">

    <div class="wrap">
        <div class="flex items-center h-16 gap-6">

            <a href="{{ route('landing') }}" class="group inline-flex min-h-11 shrink-0 items-center rounded-md" aria-label="{{ $aboutUs->company_name ?? config('app.name') }}, ke beranda">
                @include('website.partials.logo')
            </a>

            {{-- Counter windows --}}
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
                 class="relative hidden lg:block h-full ml-4">
                <span class="lamp" aria-hidden="true"
                      :class="w === 0 ? 'opacity-0' : 'opacity-100'"
                      :style="`transform: translateX(${x}px); width: ${w}px`"></span>

                <ul x-ref="row" class="relative flex items-stretch h-full">
                    @foreach($navItems as $item)
                        <li class="h-full">
                            <a href="{{ $item['href'] }}"
                               @if($item['active']) aria-current="page" @endif
                               @mouseenter="place($el)" @focus="place($el)"
                               class="flex items-center h-full px-4 text-[0.9375rem] font-semibold transition-colors duration-200
                                      {{ $item['active'] ? 'text-white' : 'text-white/70 hover:text-white focus-visible:text-white' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-3 ml-auto">

                <button type="button"
                        @click="theme = isDark ? 'light' : 'dark'"
                        :aria-label="isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
                        class="w-11 h-11 rounded-md grid place-items-center text-white/75 hover:text-white hover:bg-white/10 transition-colors cursor-pointer">
                    <span x-show="!isDark">@svg('heroicon-o-sun', 'w-5 h-5')</span>
                    <span x-show="isDark" x-cloak>@svg('heroicon-o-moon', 'w-5 h-5')</span>
                </button>

                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex items-center h-11 px-3 rounded-md text-[0.9375rem] font-semibold text-white/80 hover:text-white transition-colors">
                    Masuk
                </a>

                <a href="{{ $registerUrl }}" class="ticket hidden sm:inline-flex">Ajukan layanan</a>

                <button type="button" @click="open = !open"
                        :aria-expanded="open ? 'true' : 'false'"
                        aria-controls="nav-mobile"
                        class="lg:hidden inline-flex items-center gap-2 h-11 pl-3 pr-4 rounded-md border border-white/20
                               text-[0.9375rem] font-semibold text-white cursor-pointer">
                    <span x-show="!open">@svg('heroicon-o-bars-3', 'w-5 h-5')</span>
                    <span x-show="open" x-cloak>@svg('heroicon-o-x-mark', 'w-5 h-5')</span>
                    Menu
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile panel: large type, one thumb away --}}
    <div id="nav-mobile" x-show="open" x-cloak x-collapse class="lg:hidden border-t border-white/10 bg-nila">
        <nav aria-label="Navigasi utama (seluler)" class="wrap py-4">
            <ul>
                @foreach($navItems as $item)
                    <li class="border-b border-white/10 last:border-0">
                        <a href="{{ $item['href'] }}" @click="open = false"
                           @if($item['active']) aria-current="page" @endif
                           class="flex items-center justify-between min-h-14 font-display text-2xl font-bold tracking-tight
                                  {{ $item['active'] ? 'text-kunyit' : 'text-white' }}"
                           style="font-variation-settings: 'wdth' 90;">
                            {{ $item['label'] }}
                            @if($item['active'])
                                <span class="w-2 h-2 rounded-full bg-kunyit" aria-hidden="true"></span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <a href="{{ route('login') }}" class="btn btn-lg border border-white/25 text-white hover:bg-white/10">Masuk</a>
                <a href="{{ $registerUrl }}" class="ticket ticket-lg">Ajukan layanan</a>
            </div>
        </nav>
    </div>
</header>
