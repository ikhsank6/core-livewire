{{--
    Admin top navigation (second row under the navy header).

    Expects:
      $menuTree     nested menu tree from MenuService::getMenuTreeForUser()
      $navItems     the same tree, flattened to url/active/children for Alpine
      $currentRoute current route name

    Desktop (lg+): horizontal bar, underline marks the active item, groups open as
    dropdowns, items that do not fit move into a "More" dropdown.
    Mobile: the header hamburger (mobileMenuOpen on <body>) opens a vertical drawer.
--}}

{{-- ===== Desktop bar ===== --}}
<div class="hidden lg:block fixed top-16 inset-x-0 z-30 h-12 bg-white dark:bg-[#1e1e2d] rounded-b-2xl border-b border-gray-200 dark:border-white/6 shadow-[inset_0_1px_0_rgba(255,255,255,0.9),0_10px_22px_-8px_rgba(15,23,42,0.28)] dark:shadow-[inset_0_1px_0_rgba(255,255,255,0.06),0_10px_22px_-8px_rgba(0,0,0,0.7)]"
    x-data="{
        items: @js($navItems),
        visibleCount: {{ count($navItems) }},
        openIndex: null,
        moreOpen: false,
        get visible() { return this.items.slice(0, this.visibleCount) },
        get overflow() { return this.items.slice(this.visibleCount) },
        measure() {
            const bar = this.$refs.bar;
            const kids = this.$refs.measure ? [...this.$refs.measure.children] : [];
            if (!bar || !kids.length) return;
            const avail = bar.clientWidth;
            const widths = kids.map(k => k.offsetWidth + 4);
            if (widths.reduce((a, b) => a + b, 0) <= avail) { this.visibleCount = kids.length; return; }
            let used = 96, n = 0;
            for (const w of widths) { if (used + w > avail) break; used += w; n++; }
            this.visibleCount = Math.max(1, n);
        },
        go(event, url) {
            this.openIndex = null;
            this.moreOpen = false;
            if (event.ctrlKey || event.metaKey || event.shiftKey || url === '#') return;
            event.preventDefault();
            Livewire.navigate(url);
        }
    }"
    x-init="$nextTick(() => measure()); new ResizeObserver(() => measure()).observe($refs.bar); if (document.fonts) document.fonts.ready.then(() => measure())"
    @click.outside="openIndex = null; moreOpen = false"
    @keydown.escape.window="openIndex = null; moreOpen = false"
    aria-label="Menu utama">

    <div x-ref="bar" class="relative h-full px-4 lg:px-6 flex items-stretch gap-1">

        {{-- Hidden row used only to measure natural item widths --}}
        <div x-ref="measure" class="absolute left-0 top-0 flex gap-1 invisible pointer-events-none whitespace-nowrap" aria-hidden="true">
            <template x-for="item in items" :key="'m-' + item.name">
                <span class="inline-flex items-center gap-1.5 h-12 px-3 text-sm font-medium">
                    <span class="inline-flex shrink-0" x-html="item.icon"></span>
<span x-text="item.name"></span>
                    <svg x-show="item.children.length" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </template>
        </div>

        <template x-for="(item, idx) in visible" :key="item.name">
            <div class="relative flex"
                @mouseenter="if (item.children.length) { openIndex = idx; moreOpen = false }"
                @mouseleave="if (item.children.length) openIndex = null">

                {{-- Leaf item --}}
                <a x-show="!item.children.length" :href="item.url" @click="go($event, item.url)"
                    class="relative inline-flex items-center gap-1.5 h-12 px-3 text-sm font-medium whitespace-nowrap transition-colors"
                    :class="item.active ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'">
                    <span class="inline-flex shrink-0" x-html="item.icon"></span>
<span x-text="item.name"></span>
                    <span x-show="item.active" class="absolute inset-x-3 bottom-0 h-0.5 rounded-full bg-blue-600"></span>
                </a>

                {{-- Group item --}}
                <button x-show="item.children.length" type="button" @click="openIndex = idx; moreOpen = false"
                    class="relative inline-flex items-center gap-1.5 h-12 px-3 text-sm font-medium whitespace-nowrap transition-colors cursor-pointer"
                    :class="item.active || openIndex === idx ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    :aria-expanded="openIndex === idx">
                    <span class="inline-flex shrink-0" x-html="item.icon"></span>
<span x-text="item.name"></span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="openIndex === idx ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    <span x-show="item.active" class="absolute inset-x-3 bottom-0 h-0.5 rounded-full bg-blue-600"></span>
                </button>

                <div x-show="item.children.length && openIndex === idx" style="display: none"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute left-0 top-full min-w-[220px] p-1.5 bg-white dark:bg-[#1e1e2d] rounded-xl shadow-[0_16px_36px_-10px_rgba(15,23,42,0.35)] border border-gray-200 dark:border-white/10">
                    <template x-for="child in item.children" :key="child.name">
                        <a :href="child.url" @click="go($event, child.url)"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm whitespace-nowrap transition-colors"
                            :class="child.active
                                ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-700 dark:text-blue-300 font-semibold'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-900 dark:hover:text-white'">
                            <span class="inline-flex shrink-0 opacity-80" x-html="child.icon"></span>
<span x-text="child.name"></span>
                        </a>
                    </template>
                </div>
            </div>
        </template>

        {{-- More (items that do not fit) --}}
        <div x-show="overflow.length" style="display: none" class="relative flex"
            @mouseenter="moreOpen = true; openIndex = null" @mouseleave="moreOpen = false">
            <button type="button" @click="moreOpen = true; openIndex = null"
                class="inline-flex items-center gap-1.5 h-12 px-3 text-sm font-medium whitespace-nowrap cursor-pointer transition-colors"
                :class="moreOpen ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                :aria-expanded="moreOpen">
                More
                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="moreOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="moreOpen" style="display: none"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute right-0 top-full min-w-[240px] max-h-[70vh] overflow-y-auto p-1.5 bg-white dark:bg-[#1e1e2d] rounded-xl shadow-[0_16px_36px_-10px_rgba(15,23,42,0.35)] border border-gray-200 dark:border-white/10">
                <template x-for="item in overflow" :key="'o-' + item.name">
                    <div>
                        <a x-show="!item.children.length" :href="item.url" @click="go($event, item.url)"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm whitespace-nowrap transition-colors"
                            :class="item.active
                                ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-700 dark:text-blue-300 font-semibold'
                                : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-900 dark:hover:text-white'">
                            <span class="inline-flex shrink-0" x-html="item.icon"></span>
<span x-text="item.name"></span>
                        </a>
                        <div x-show="item.children.length">
                            <p class="px-3 pt-2 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500" x-text="item.name"></p>
                            <template x-for="child in item.children" :key="child.name">
                                <a :href="child.url" @click="go($event, child.url)"
                                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm whitespace-nowrap transition-colors"
                                    :class="child.active
                                        ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-700 dark:text-blue-300 font-semibold'
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-900 dark:hover:text-white'">
                                    <span class="inline-flex shrink-0 opacity-80" x-html="child.icon"></span>
<span x-text="child.name"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

{{-- ===== Mobile drawer ===== --}}
<div class="lg:hidden" x-show="mobileMenuOpen" x-cloak style="display: none">
    <div class="fixed inset-x-0 bottom-0 top-16 z-30 bg-black/60 backdrop-blur-sm"
        x-show="mobileMenuOpen"
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileMenuOpen = false"></div>

    <nav class="fixed left-0 bottom-0 top-16 z-30 w-72 max-w-[85vw] overflow-y-auto py-3 px-2 space-y-0.5 bg-white dark:bg-[#1e1e2d] border-r border-gray-200 dark:border-white/6"
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        aria-label="Menu utama (mobile)">

        @foreach($menuTree as $menu)
            @if(empty($menu['children']))
                @php $isActive = $currentRoute === $menu['route']; @endphp
                <a href="{{ \App\Services\MenuService::safeRoute($menu['route']) }}" wire:navigate
                    @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $isActive ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-900 dark:hover:text-white' }}">
                    @if($menu['icon'])
                        @svg('heroicon-o-' . $menu['icon'], 'w-5 h-5 shrink-0')
                    @else
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    @endif
                    <span class="truncate">{{ $menu['name'] }}</span>
                </a>
            @else
                @php $hasActiveChild = collect($menu['children'])->contains('route', $currentRoute); @endphp
                <div x-data="{ expanded: {{ $hasActiveChild ? 'true' : 'false' }} }">
                    <button type="button" @click="expanded = !expanded"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-white/6 {{ $hasActiveChild ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                        @if($menu['icon'])
                            @svg('heroicon-o-' . $menu['icon'], 'w-5 h-5 shrink-0')
                        @else
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        @endif
                        <span class="flex-1 text-left truncate">{{ $menu['name'] }}</span>
                        <svg class="w-3.5 h-3.5 shrink-0 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="expanded" x-collapse class="mt-0.5 space-y-0.5 pl-8 pr-1">
                        @foreach($menu['children'] as $child)
                            @php $isChildActive = $currentRoute === $child['route']; @endphp
                            <a href="{{ \App\Services\MenuService::safeRoute($child['route']) }}" wire:navigate
                                @click="mobileMenuOpen = false"
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors {{ $isChildActive ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-700 dark:text-blue-300 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-800 dark:hover:text-white' }}">
                                {{ $child['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>
</div>
