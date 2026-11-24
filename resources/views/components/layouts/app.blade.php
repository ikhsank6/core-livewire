<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
@include('partials.fouc-prevention')

<head>
    @include('partials.meta-base')

    <title>{{ $title ?? $aboutUs->company_name ?? config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')
    @include('partials.fonts')
    @include('partials.filament-assets')
    @include('partials.alpine-cloak')

    <style>
        /* Canvas colour follows the theme, so a page swap never shows a white frame in dark mode */
        html { background-color: #f9fafb; }
        html.dark { background-color: #1b1c22; }


        [data-flux-modal-backdrop] {
            backdrop-filter: blur(8px) !important;
            background-color: rgba(27, 28, 34, 0.8) !important;
        }

        @keyframes breadcrumbSlideIn {
            from { opacity: 0; transform: translateX(-12px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .breadcrumb-animate {
            animation: breadcrumbSlideIn 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }
    </style>

    @include('partials.theme-scripts')
</head>

<body class="antialiased bg-gray-50 dark:bg-[#1b1c22] text-gray-900 dark:text-white"
    x-data="{ mobileMenuOpen: false }"
    @resize.window="if (window.innerWidth >= 1024) mobileMenuOpen = false">

    @php
        $menuTree = app(\App\Services\MenuService::class)->getMenuTreeForUser();
        $currentRoute = request()->route()?->getName() ?? '';

        // Icon markup from the menu master (heroicon name). Falls back to a generic glyph
        // (top level) or a dot (child) when the master has no icon or the name is unknown.
        $menuIcon = function (?string $name, string $class, bool $dotFallback = false): string {
            if ($name) {
                try {
                    return svg('heroicon-o-'.$name, $class)->toHtml();
                } catch (\Throwable $e) {
                    // fall through to the fallback below
                }
            }

            return $dotFallback
                ? '<span class="block w-1.5 h-1.5 rounded-full bg-current"></span>'
                : '<svg class="'.$class.'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>';
        };

        // Flattened menu (url + active flags) consumed by the Alpine top bar and the menu search.
        $navItems = collect($menuTree)->map(function ($menu) use ($currentRoute, $menuIcon) {
            $children = collect($menu['children'] ?? [])->map(fn ($child) => [
                'name' => $child['name'],
                'icon' => $menuIcon($child['icon'] ?? null, 'w-4 h-4', true),
                'url' => \App\Services\MenuService::safeRoute($child['route']),
                'active' => $currentRoute === $child['route'],
            ])->values()->all();

            return [
                'name' => $menu['name'],
                'icon' => $menuIcon($menu['icon'] ?? null, 'w-[18px] h-[18px]'),
                'url' => empty($children) ? \App\Services\MenuService::safeRoute($menu['route']) : null,
                'active' => empty($children)
                    ? $currentRoute === $menu['route']
                    : collect($children)->contains('active', true),
                'children' => $children,
            ];
        })->values()->all();

        $flatMenu = collect($navItems)->flatMap(fn ($item) => empty($item['children'])
            ? [['name' => $item['name'], 'parent' => null, 'url' => $item['url']]]
            : collect($item['children'])->map(fn ($child) => [
                'name' => $child['name'],
                'parent' => $item['name'],
                'url' => $child['url'],
            ])->all()
        )->values()->all();
    @endphp

    {{-- =====================
         MAIN WRAPPER
         ===================== --}}
    <div class="min-h-screen">

        {{-- =====================
             HEADER / NAVBAR
             ===================== --}}
        <nav class="fixed top-0 right-0 left-0 z-40 bg-linear-to-b from-[#2349ad] via-[#1a3e9a] to-[#173684] border-b-2 border-[#102a6e] shadow-[inset_0_1px_0_rgba(255,255,255,0.22),inset_0_-1px_0_rgba(255,255,255,0.08),0_6px_18px_-4px_rgba(10,25,70,0.6)] transition-all duration-300">
            <div class="px-4 h-16 flex items-center gap-x-3">

                {{-- Mobile hamburger — opens the vertical menu drawer --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors shrink-0"
                    :aria-expanded="mobileMenuOpen" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Logo / Brand (two lines: name + tagline) --}}
                <a href="/" class="group flex items-center gap-3 min-w-0 shrink-0 rounded-md">
                    @if($aboutUs?->logo)
                        <img src="{{ Storage::url($aboutUs->logo) }}" alt="Logo" class="h-9 w-9 rounded-lg object-contain shrink-0">
                    @else
                        {{-- Same certification seal as the public website --}}
                        <svg class="w-9 h-9 shrink-0" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                            <circle cx="18" cy="18" r="16" stroke="#FF8A70" stroke-width="2.4" stroke-linecap="round" stroke-dasharray="0.1 3.6"/>
                            <circle cx="18" cy="18" r="12.5" fill="#E4472D"/>
                            <path d="M11.8 18.4l4.4 4.4 8.2-9.2" stroke="#1B0B07" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                    <div class="min-w-0 hidden sm:block">
                        <p class="font-bold text-white text-sm truncate leading-tight">{{ $aboutUs?->company_name ?? 'Admin' }}</p>
                        <p class="text-[10px] font-medium uppercase tracking-wider text-blue-200/80 truncate leading-tight">Lembaga Sertifikasi Profesi</p>
                    </div>
                </a>

                {{-- Global menu search: results dropdown, Ctrl+K / Cmd+K focuses it --}}
                <div class="hidden lg:flex w-80">
                    <div class="relative w-full"
                        x-data="{
                            q: '',
                            open: false,
                            active: 0,
                            items: @js($flatMenu),
                            get results() {
                                const s = this.q.trim().toLowerCase();
                                if (!s) return [];
                                return this.items.filter(i => i.name.toLowerCase().includes(s) || (i.parent || '').toLowerCase().includes(s)).slice(0, 8);
                            },
                            go(item) {
                                if (!item || item.url === '#') return;
                                this.q = '';
                                this.open = false;
                                Livewire.navigate(item.url);
                            }
                        }"
                        @click.outside="open = false"
                        @keydown.window="if (($event.ctrlKey || $event.metaKey) && $event.key.toLowerCase() === 'k') { $event.preventDefault(); $refs.search.focus(); }">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-blue-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" placeholder="Cari menu..." autocomplete="off"
                            x-model="q"
                            x-ref="search"
                            @focus="open = true"
                            @input="open = true; active = 0"
                            @keydown.arrow-down.prevent="active = Math.min(active + 1, results.length - 1)"
                            @keydown.arrow-up.prevent="active = Math.max(active - 1, 0)"
                            @keydown.enter.prevent="go(results[active])"
                            @keydown.escape="open = false; q = ''; $refs.search.blur()"
                            class="w-full pl-9 pr-16 py-2 text-sm rounded-lg bg-[#0f2a78]/35 border border-white/15 border-b-white/25 shadow-[inset_0_2px_5px_rgba(5,15,60,0.5),inset_0_0_0_1px_rgba(5,15,60,0.15)] text-white placeholder-blue-200/60 focus:outline-none focus:border-white/50 focus:ring-1 focus:ring-white/40 transition-colors">
                        {{-- Clear button (shown while typing) --}}
                        <button x-show="q !== ''"
                            x-on:click="q = ''; $refs.search.focus()"
                            class="absolute inset-y-0 right-2 flex items-center text-blue-200/70 hover:text-white cursor-pointer focus:outline-none"
                            title="Hapus pencarian">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        {{-- Shortcut hint (hidden while typing) --}}
                        <kbd x-show="q === ''"
                            class="absolute inset-y-0 right-2 my-auto flex items-center h-5 px-1.5 rounded border border-white/25 border-b-black/30 bg-linear-to-b from-white/20 to-white/5 shadow-[0_1px_0_rgba(5,15,60,0.55),inset_0_1px_0_rgba(255,255,255,0.25)] text-[10px] font-medium text-blue-100/90">
                            Ctrl+K
                        </kbd>

                        {{-- Results --}}
                        <div x-show="open && q.trim() !== ''" style="display: none"
                            class="absolute left-0 right-0 top-full mt-2 z-50 p-1.5 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700">
                            <template x-for="(item, i) in results" :key="item.url + item.name">
                                <button type="button" @click="go(item)" @mouseenter="active = i"
                                    class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-sm text-left cursor-pointer transition-colors"
                                    :class="i === active ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'">
                                    <span class="truncate" x-text="item.name"></span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500 truncate" x-text="item.parent"></span>
                                </button>
                            </template>
                            <p x-show="results.length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">Menu tidak ditemukan</p>
                        </div>
                    </div>
                </div>
                {{-- Spacer: absorbs remaining space so the right side sits flush at the edge,
                     whether or not the search box above is visible. --}}
                <div class="flex-1"></div>

                {{-- Right side --}}
                <div class="flex items-center gap-x-1">

                    {{-- Date --}}
                    <div x-data="{ date: '' }" x-init="
                        const d = new Date();
                        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                        date = days[d.getDay()] + ', ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                    " class="hidden lg:flex items-center text-sm font-medium text-blue-100/90 dark:text-gray-400 mr-2">
                        <span x-text="date"></span>
                    </div>

                    {{-- Notification Bell --}}
                    <livewire:layout.notification-bell />

                    {{-- Profile Dropdown --}}
                    <div class="relative" x-data="{
                        profileOpen: false,
                        get isDark() { return $store.theme ? $store.theme.isDark : (localStorage.getItem('theme') === 'dark') },
                        toggle() {
                            if ($store.theme) {
                                $store.theme.toggle();
                            } else {
                                const newTheme = this.isDark ? 'light' : 'dark';
                                localStorage.setItem('theme', newTheme);
                                document.documentElement.classList.toggle('dark', newTheme === 'dark');
                            }
                        }
                    }" @click.away="profileOpen = false">
                        <button @click="profileOpen = !profileOpen"
                            class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar"
                                    class="h-8 w-8 rounded-full object-cover ring-2 ring-white/20">
                            @else
                                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white uppercase ring-2 ring-white/20">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="hidden sm:flex flex-col text-left leading-tight">
                                <span class="text-sm font-semibold text-white max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <span class="text-xs text-blue-100/80 dark:text-gray-400 max-w-[120px] truncate">{{ auth()->user()->role?->name ?? 'No Role' }}</span>
                            </div>
                            <svg class="w-4 h-4 text-blue-200 dark:text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="profileOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            x-cloak
                            class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden"
                            style="top: calc(100% + 4px)">

                            <div class="px-4 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar"
                                            class="h-11 w-11 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-600">
                                    @else
                                        <div class="h-11 w-11 rounded-xl bg-blue-600 flex items-center justify-center text-sm font-bold text-white uppercase">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                        <span class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            {{ auth()->user()->role?->name ?? 'No Role' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('profile') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profile
                                </a>
                                <a href="{{ route('password.change') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    Change Password
                                </a>

                                {{-- Theme Toggle --}}
                                <button @click="toggle()"
                                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <svg x-show="isDark" x-cloak class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <svg x-show="!isDark" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                        </svg>
                                        <span x-text="isDark ? 'Mode Terang' : 'Mode Gelap'"></span>
                                    </div>
                                </button>

                                @if(auth()->user()->roles->count() > 1)
                                    <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                    <div class="px-4 py-1">
                                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Switch Role</p>
                                    </div>
                                    @foreach(auth()->user()->roles as $role)
                                        <a href="{{ route('roles.switch', $role) }}"
                                            class="flex items-center gap-3 px-4 py-2 text-sm transition-colors
                                                {{ auth()->user()->role_id == $role->id
                                                    ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20'
                                                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                            <svg class="w-4 h-4 {{ auth()->user()->role_id == $role->id ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if(auth()->user()->role_id == $role->id)
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                @endif
                                            </svg>
                                            {{ $role->name }}
                                        </a>
                                    @endforeach
                                @endif

                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                                <button @click="$dispatch('open-modal', { name: 'logout-modal' }); profileOpen = false"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        @include('partials.admin-topnav', ['menuTree' => $menuTree, 'navItems' => $navItems, 'currentRoute' => $currentRoute])


        {{-- =====================
             MAIN CONTENT
             ===================== --}}
        <main class="pt-16 lg:pt-28 min-h-screen flex flex-col">
            <div class="flex-1">
                {{-- Breadcrumbs --}}
                @isset($breadcrumbs)
                    <div id="breadcrumb-wrapper" class="px-6 lg:px-8 pt-6 pb-3 max-w-7xl mx-auto breadcrumb-animate">
                        <nav aria-label="Breadcrumb">
                            <ol class="flex items-center gap-1.5 text-sm">
                                {{ $breadcrumbs }}
                            </ol>
                        </nav>
                    </div>
                @endisset

                <div class="{{ isset($breadcrumbs) ? 'px-6 lg:px-8 pb-6 lg:pb-8 pt-3 mx-auto max-w-7xl' : 'p-6 lg:p-8 mx-auto max-w-7xl' }}">
                    {{ $slot }}
                </div>
            </div>

            {{-- Footer --}}
            <footer class="px-6 lg:px-8 py-6 mt-auto">
                <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <p>&copy; {{ date('Y') }} <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $aboutUs->company_name ?? 'Laravel Admin' }}</span>. All Rights Reserved.</p>
                    <div class="flex items-center gap-x-4">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 font-semibold tracking-wide uppercase scale-90">
                            v3.0.0
                        </span>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    @include('partials.toast-notifications')

    {{-- Logout confirmation: compact single-surface dialog (opened via the open-modal event) --}}
    <div x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.name === 'logout-modal') show = true"
        x-on:close-modal.window="if ($event.detail.name === 'logout-modal') show = false"
        x-show="show" style="display: none;"
        class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="logout-title">

        <div x-show="show"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm" aria-hidden="true"></div>

        {{-- Clicking outside the card intentionally does nothing: close only via X, Batal, or Logout --}}
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div x-show="show"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="relative w-full max-w-sm px-6 pt-6 pb-5 text-center rounded-2xl bg-white dark:bg-[#1e1e2d] border border-zinc-200 dark:border-[#2d2d3a] shadow-[0_24px_60px_-12px_rgba(15,23,42,0.45)]">

                <button type="button" @click="show = false" aria-label="Tutup"
                    class="absolute top-3 right-3 p-1.5 rounded-full text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 dark:hover:text-white dark:hover:bg-zinc-800 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 dark:bg-red-900/20 ring-8 ring-red-50/60 dark:ring-red-900/10">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>

                <h3 id="logout-title" class="mt-4 text-lg font-bold text-zinc-900 dark:text-white">Konfirmasi Logout</h3>
                <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Apakah Anda yakin ingin keluar dari aplikasi?</p>

                <div class="mt-6 flex gap-3">
                    <button type="button" @click="show = false"
                        class="flex-1 h-10 inline-flex items-center justify-center gap-1.5 rounded-full text-sm font-semibold bg-zinc-100 text-zinc-700 hover:bg-zinc-200 border border-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700 dark:border-zinc-700 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <a href="{{ route('logout') }}"
                        class="flex-1 h-10 inline-flex items-center justify-center gap-1.5 rounded-full text-sm font-semibold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/25 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Ya, Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <x-ui.delete-confirm-modal />

    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('livewire:navigated', () => {
            const el = document.getElementById('breadcrumb-wrapper');
            if (!el) return;
            // Re-trigger animation by removing and re-adding the class
            el.classList.remove('breadcrumb-animate');
            void el.offsetWidth; // force reflow
            el.classList.add('breadcrumb-animate');
        });
    </script>

    @fluxScripts
    @filamentScripts
</body>

</html>
