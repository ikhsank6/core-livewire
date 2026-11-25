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

{{-- A dark navy bar. A key-shaped marker slides to the item you point at: raised while it moves,
     pressed in with a gold underline when it rests on the current page. --}}
<header x-data="{ open: false }"
        @keydown.escape.window="open = false"
        class="on-dark sticky top-0 z-50 bg-linear-to-b from-navy-2 to-navy text-white border-b border-[#0D192B]
               shadow-[inset_0_1px_0_rgba(255,255,255,0.1),0_10px_20px_-8px_rgba(4,14,28,0.55)]">

    <div class="wrap">
        <div class="flex items-center h-[3.25rem] gap-6">

            <a href="{{ route('landing') }}" class="group inline-flex min-h-11 shrink-0 items-center rounded-md" aria-label="{{ $aboutUs->company_name ?? config('app.name') }}, ke beranda">
                @include('website.partials.logo', ['textClass' => 'font-extrabold text-lg tracking-tight text-white'])
            </a>

            <nav aria-label="Navigasi utama"
                 x-data="{
                    x: 0,
                    w: 0,
                    pressed: true,
                    place(el) { this.x = el.offsetLeft; this.w = el.offsetWidth; this.pressed = el.getAttribute('aria-current') === 'page'; },
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
                      :class="[w === 0 ? 'opacity-0' : 'opacity-100', pressed ? 'is-pressed' : '']"
                      :style="`transform: translateX(${x}px); width: ${w}px`"></span>

                <ul x-ref="row" class="relative flex items-stretch h-full">
                    @foreach($navItems as $item)
                        <li class="h-full">
                            <a href="{{ $item['href'] }}"
                               @if($item['active']) aria-current="page" @endif
                               @mouseenter="place($el)" @focus="place($el)"
                               class="flex items-center h-full px-3.5 text-[0.9375rem] font-semibold transition-colors duration-200
                                      {{ $item['active'] ? 'text-white' : 'text-[#B9BFC7] hover:text-white focus-visible:text-white' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-2 ml-auto">

                <div class="theme-track" role="group" aria-label="Tema tampilan">
                    <button type="button" @click="theme = 'light'"
                            :aria-pressed="!isDark ? 'true' : 'false'"
                            aria-label="Mode terang"
                            :class="!isDark ? 'bg-gold text-navy shadow-[0_1px_2px_rgba(0,0,0,0.4),inset_0_1px_0_rgba(255,255,255,0.4)]' : 'text-[#D5DAE3] hover:text-white'"
                            class="relative grid h-7 w-7 cursor-pointer place-items-center rounded-full transition-colors before:absolute before:-inset-y-2 before:inset-x-0">
                        @svg('heroicon-o-sun', 'w-3.5 h-3.5')
                    </button>
                    <button type="button" @click="theme = 'dark'"
                            :aria-pressed="isDark ? 'true' : 'false'"
                            aria-label="Mode gelap"
                            :class="isDark ? 'bg-gold text-navy shadow-[0_1px_2px_rgba(0,0,0,0.4),inset_0_1px_0_rgba(255,255,255,0.4)]' : 'text-[#D5DAE3] hover:text-white'"
                            class="relative grid h-7 w-7 cursor-pointer place-items-center rounded-full transition-colors before:absolute before:-inset-y-2 before:inset-x-0">
                        @svg('heroicon-o-moon', 'w-3.5 h-3.5')
                    </button>
                </div>

                <a href="{{ route('login') }}"
                   class="hidden sm:inline-flex min-h-11 items-center rounded-md px-3 text-[0.9375rem] font-semibold text-white/90 underline decoration-transparent underline-offset-[6px] transition-colors hover:text-white hover:decoration-gold">
                    Masuk
                </a>

                <button type="button" @click="open = !open"
                        :aria-expanded="open ? 'true' : 'false'"
                        aria-controls="nav-mobile"
                        class="lg:hidden inline-flex items-center gap-2 h-11 pl-3 pr-4 rounded-md border border-white/25
                               text-[0.9375rem] font-semibold text-white cursor-pointer">
                    <span x-show="!open">@svg('heroicon-o-bars-3', 'w-5 h-5')</span>
                    <span x-show="open" x-cloak>@svg('heroicon-o-x-mark', 'w-5 h-5')</span>
                    Menu
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile panel: large type, one thumb away --}}
    <div id="nav-mobile" x-show="open" x-cloak x-collapse class="lg:hidden border-t border-white/10 bg-navy">
        <nav aria-label="Navigasi utama (seluler)" class="wrap py-4">
            <ul>
                @foreach($navItems as $item)
                    <li class="border-b border-white/10 last:border-0">
                        <a href="{{ $item['href'] }}" @click="open = false"
                           @if($item['active']) aria-current="page" @endif
                           class="flex min-h-14 items-center justify-between text-2xl font-extrabold tracking-tight
                                  {{ $item['active'] ? 'text-gold-soft' : 'text-white' }}">
                            {{ $item['label'] }}
                            @if($item['active'])
                                <span class="h-2 w-2 rounded-full bg-gold" aria-hidden="true"></span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-5">
                <a href="{{ route('login') }}" class="btn btn-lg btn-accent w-full">Masuk</a>
            </div>
        </nav>
    </div>
</header>
