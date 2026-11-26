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
        <nav class="fixed top-0 right-0 left-0 z-40 bg-linear-to-b from-[#273955] to-[#162948] border-b-2 border-[#0D192B] shadow-[inset_0_1px_0_rgba(255,255,255,0.1),0_10px_20px_-8px_rgba(4,14,28,0.55)] transition-all duration-300">
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
                            <circle cx="18" cy="18" r="16" stroke="#F0CB6B" stroke-width="2.4" stroke-linecap="round" stroke-dasharray="0.1 3.6"/>
                            <circle cx="18" cy="18" r="12.5" fill="#E7BA4B"/>
                            <path d="M11.8 18.4l4.4 4.4 8.2-9.2" stroke="#14213A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @endif
                    <div class="min-w-0 hidden sm:block">
                        <p class="font-bold text-white text-sm truncate leading-tight">{{ $aboutUs?->company_name ?? 'Admin' }}</p>
                        <p class="text-[10px] font-medium uppercase tracking-wider text-white/85 truncate leading-tight">Lembaga Sertifikasi Profesi</p>
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
                            class="w-full pl-9 pr-16 py-2 text-sm rounded-lg bg-white/10 hover:bg-white/15 focus:bg-white/15 border border-white/20 text-white placeholder-blue-100/60 focus:outline-none focus:border-white/40 focus:ring-1 focus:ring-white/40 transition-colors">
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
                            class="absolute inset-y-0 right-2 my-auto flex items-center h-5 px-1.5 rounded border border-white/20 bg-white/10 text-[10px] font-medium text-white/85">
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
                    " class="hidden lg:flex items-center text-sm font-medium text-white/90 dark:text-gray-400 mr-2">
                        <span x-text="date"></span>
                    </div>

                    {{-- Notification Bell --}}
                    <livewire:layout.notification-bell />

                    @include('partials.account-menu')
                </div>
            </div>
        </nav>

        @include('partials.admin-topnav', ['menuTree' => $menuTree, 'navItems' => $navItems, 'currentRoute' => $currentRoute])


        {{-- =====================
             MAIN CONTENT
             ===================== --}}
        <main class="pt-16 lg:pt-28 min-h-screen flex flex-col">
            <div class="flex-1">
                @include('partials.temporary-password-banner')

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
                            v3.3.0
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

        {{-- Clicking outside the card intentionally does nothing: close only via X, Batal, or Ya, keluar --}}
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

                <h3 id="logout-title" class="mt-4 text-lg font-bold text-zinc-900 dark:text-white">Keluar dari akun?</h3>
                <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Anda perlu masuk lagi untuk melanjutkan.</p>

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
                        Ya, keluar
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
