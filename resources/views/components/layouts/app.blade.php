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

    @include('partials.theme-scripts', ['includeSidebarState' => true])
</head>

<body class="antialiased bg-gray-50 dark:bg-[#1b1c22] text-gray-900 dark:text-white"
    x-data="{
        sidebarOpen: window.innerWidth >= 1024,
        sidebarCollapsed: false,
        searchQuery: '',
        get sidebarWidth() { return this.sidebarCollapsed ? 'w-16' : 'w-64' }
    }"
    x-init="
        if ($store.sidebarState) { sidebarOpen = $store.sidebarState.open }
        $watch('sidebarOpen', val => { if ($store.sidebarState) $store.sidebarState.open = val })

        // Auto-hide/show sidebar based on screen width (autohide if width < 1024px)
        const checkResponsive = () => {
            if (window.innerWidth < 1024) {
                sidebarOpen = false;
            } else {
                sidebarOpen = true;
            }
        };
        checkResponsive();
        window.addEventListener('resize', checkResponsive);
    ">

    {{-- =====================
         SIDEBAR
         ===================== --}}
    <aside
        :class="[
            'fixed top-0 left-0 z-40 h-screen transition-all duration-300 ease-in-out',
            sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            sidebarCollapsed ? 'w-16' : 'w-64'
        ]"
        aria-label="Sidebar">

        <div class="h-full flex flex-col bg-white dark:bg-[#1e1e2d] border-r border-gray-200 dark:border-white/6 overflow-hidden">

            {{-- Logo / Brand --}}
            <div class="h-16 flex items-center shrink-0 border-b border-gray-100 dark:border-white/6 overflow-hidden"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between px-4'">

                <a href="/" class="flex items-center gap-2.5 min-w-0" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    @if($aboutUs?->logo)
                        <img src="{{ Storage::url($aboutUs->logo) }}" alt="Logo" class="h-8 w-8 rounded-lg object-contain shrink-0">
                    @else
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white shrink-0 shadow-sm">
                            <span class="font-bold text-sm">{{ strtoupper(substr($aboutUs?->company_name ?? 'A', 0, 2)) }}</span>
                        </div>
                    @endif
                    <span class="font-semibold text-gray-900 dark:text-white text-sm truncate">{{ $aboutUs?->company_name ?? 'Admin' }}</span>
                </a>

                {{-- Icon only when collapsed --}}
                <div x-show="sidebarCollapsed" x-cloak class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white shrink-0 shadow-sm">
                    <span class="font-bold text-sm">{{ strtoupper(substr($aboutUs?->company_name ?? 'A', 0, 2)) }}</span>
                </div>
            </div>

            {{-- Search (hidden when collapsed) --}}
            <div class="px-3 pt-3 pb-1" x-show="!sidebarCollapsed" x-cloak>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Cari menu..."
                        x-model="searchQuery"
                        class="w-full pl-9 pr-9 py-2 text-sm rounded-lg bg-gray-100 dark:bg-white/6 border border-gray-200 dark:border-white/8 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    {{-- Clear search button --}}
                    <button x-show="searchQuery !== ''" 
                        x-on:click="searchQuery = ''"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer focus:outline-none"
                        title="Hapus pencarian">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-2 space-y-0.5"
                :class="sidebarCollapsed ? 'px-1.5' : 'px-2'">
                @php
                    $menuService = app(\App\Services\MenuService::class);
                    $menuTree = $menuService->getMenuTreeForUser();
                    $currentRoute = request()->route()?->getName() ?? '';
                @endphp

                @foreach($menuTree as $menu)
                    @if(empty($menu['children']))
                        @php $isActive = $currentRoute === $menu['route']; @endphp
                        <div
                            x-show="searchQuery === '' || '{{ strtolower(addslashes($menu['name'])) }}'.includes(searchQuery.toLowerCase())"
                            @mouseenter="if(sidebarCollapsed) $dispatch('sidebar-tip', { label: '{{ addslashes($menu['name']) }}', top: $el.getBoundingClientRect().top + $el.getBoundingClientRect().height / 2 })"
                            @mouseleave="$dispatch('sidebar-tip-hide')">
                            <a href="{{ \App\Services\MenuService::safeRoute($menu['route']) }}" wire:navigate
                                class="flex items-center rounded-lg text-sm font-medium transition-all duration-200"
                                :class="[
                                    sidebarCollapsed
                                        ? 'w-10 h-10 justify-center mx-auto'
                                        : 'gap-3 px-3 py-2.5',
                                    {!! $isActive ? 'true' : 'false' !!}
                                        ? 'bg-blue-600 text-white shadow-sm'
                                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-900 dark:hover:text-white'
                                ]">
                                <div class="shrink-0 flex items-center justify-center w-5 h-5">
                                    @if($menu['icon'])
                                        @svg('heroicon-o-' . $menu['icon'], 'w-5 h-5')
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                    @endif
                                </div>
                                <span x-show="!sidebarCollapsed" x-cloak class="whitespace-nowrap truncate">{{ $menu['name'] }}</span>
                            </a>
                        </div>

                    @else
                        @php 
                            $hasActiveChild = collect($menu['children'])->contains('route', $currentRoute); 
                            $childNamesJson = json_encode(collect($menu['children'])->map(fn($c) => strtolower($c['name']))->toArray());
                            $childrenData = collect($menu['children'])->map(fn($c) => [
                                'name' => $c['name'],
                                'route' => \App\Services\MenuService::safeRoute($c['route']),
                                'isActive' => $currentRoute === $c['route']
                            ])->toArray();
                        @endphp
                        <div x-data="{ 
                                expanded: {!! $hasActiveChild ? 'true' : 'false' !!},
                                childNames: {{ $childNamesJson }}
                            }"
                            data-has-active="{!! $hasActiveChild ? 'true' : 'false' !!}"
                            x-on:livewire:navigated.window="expanded = ($el.getAttribute('data-has-active') === 'true')"
                            x-show="searchQuery === '' || 
                                    '{{ strtolower(addslashes($menu['name'])) }}'.includes(searchQuery.toLowerCase()) || 
                                    childNames.some(name => name.includes(searchQuery.toLowerCase()))"
                            x-effect="
                                if (searchQuery !== '') {
                                    if (childNames.some(name => name.includes(searchQuery.toLowerCase()))) {
                                        expanded = true;
                                    }
                                } else {
                                    expanded = ($el.getAttribute('data-has-active') === 'true');
                                }
                            "
                            class="relative group/group">
                            <div
                                @mouseenter="
                                    if(sidebarCollapsed) { 
                                        $dispatch('show-floating-menu', { 
                                            name: '{{ addslashes($menu['name']) }}', 
                                            children: {{ json_encode($childrenData) }}, 
                                            top: $el.getBoundingClientRect().top 
                                        }); 
                                    } else {
                                        $dispatch('sidebar-tip', { label: '{{ addslashes($menu['name']) }}', top: $el.getBoundingClientRect().top + $el.getBoundingClientRect().height / 2 });
                                    }
                                "
                                @mouseleave="
                                    if(sidebarCollapsed) { 
                                        $dispatch('hide-floating-menu'); 
                                    } else {
                                        $dispatch('sidebar-tip-hide');
                                    }
                                ">
                                <button
                                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; $nextTick(() => expanded = true) } else { expanded = !expanded }"
                                    class="w-full flex items-center rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-900 dark:hover:text-white"
                                    :class="[
                                        sidebarCollapsed
                                            ? 'w-10 h-10 justify-center mx-auto'
                                            : 'gap-3 px-3 py-2.5',
                                        {!! $hasActiveChild ? 'true' : 'false' !!}
                                            ? (sidebarCollapsed ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-600 dark:text-blue-400' : 'text-blue-600 dark:text-blue-400')
                                            : 'text-gray-600 dark:text-gray-300'
                                    ]">
                                    <div class="shrink-0 flex items-center justify-center w-5 h-5">
                                        @if($menu['icon'])
                                            @svg('heroicon-o-' . $menu['icon'], 'w-5 h-5')
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <span x-show="!sidebarCollapsed" x-cloak class="flex-1 text-left whitespace-nowrap truncate">{{ $menu['name'] }}</span>
                                    <svg x-show="!sidebarCollapsed" x-cloak class="w-3.5 h-3.5 transition-transform duration-200 shrink-0"
                                        :class="expanded ? 'rotate-180' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Children --}}
                            <div x-show="expanded && !sidebarCollapsed"
                                x-collapse
                                class="mt-0.5 space-y-0.5 pl-7 pr-1">
                                @foreach($menu['children'] as $child)
                                    @php $isChildActive = $currentRoute === $child['route']; @endphp
                                    <a href="{{ \App\Services\MenuService::safeRoute($child['route']) }}" wire:navigate
                                        x-show="searchQuery === '' || 
                                                '{{ strtolower(addslashes($menu['name'])) }}'.includes(searchQuery.toLowerCase()) || 
                                                '{{ strtolower(addslashes($child['name'])) }}'.includes(searchQuery.toLowerCase())"
                                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all duration-200"
                                        :class="{{ $isChildActive ? 'true' : 'false' }}
                                            ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-700 dark:text-blue-300 font-semibold'
                                            : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/6 hover:text-gray-800 dark:hover:text-white'">
                                        @if($child['icon'])
                                            <div class="shrink-0">
                                                @svg('heroicon-o-' . $child['icon'], 'w-4 h-4')
                                            </div>
                                        @else
                                            <div class="w-1.5 h-1.5 rounded-full bg-current shrink-0 ml-0.5 mt-0.5"></div>
                                        @endif
                                        <span>{{ $child['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </nav>


        </div>

        {{-- Floating Expand/Collapse Button on Border (Notion-style) --}}
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="absolute top-[18px] -right-3.5 z-50 hidden lg:flex items-center justify-center w-7 h-7 bg-white dark:bg-[#1e1e2d] border border-gray-200 dark:border-[#3d3d4e] rounded-full shadow-sm text-gray-600 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white cursor-pointer transition-all duration-300 focus:outline-none hover:scale-110 hover:shadow-md"
            :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
            <svg class="w-4 h-4 transition-transform duration-300"
                :class="sidebarCollapsed ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </aside>

    {{-- Global fixed tooltip for collapsed sidebar --}}
    <div
        x-data="{ show: false, label: '', top: 0 }"
        @sidebar-tip.window="show = true; label = $event.detail.label; top = $event.detail.top"
        @sidebar-tip-hide.window="show = false"
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 translate-x-1"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-1"
        :style="`top: ${top}px; transform: translateY(-50%); left: 68px;`"
        class="fixed z-[9999] pointer-events-none px-3 py-1.5 bg-gray-900 dark:bg-gray-700 text-white text-xs font-semibold rounded-lg shadow-xl border border-white/10 whitespace-nowrap">
        <span x-text="label"></span>
    </div>

    {{-- Global Fixed Floating Sub-Menu Dropdown for Collapsed Sidebar --}}
    <div
        x-data="{ 
            show: false, 
            name: '', 
            children: [], 
            top: 0, 
            hoverMenu: false, 
            hoverTrigger: false,
            updateShow() {
                this.show = (this.hoverTrigger || this.hoverMenu);
            }
        }"
        @show-floating-menu.window="
            name = $event.detail.name; 
            children = $event.detail.children; 
            top = $event.detail.top; 
            hoverTrigger = true; 
            updateShow();
        "
        @hide-floating-menu.window="
            hoverTrigger = false; 
            setTimeout(() => { updateShow(); }, 80);
        "
        x-show="show"
        x-cloak
        @mouseenter="hoverMenu = true; updateShow();"
        @mouseleave="hoverMenu = false; hoverTrigger = false; updateShow();"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        :style="`top: ${top}px; left: 60px;`"
        class="fixed z-[9999] min-w-[200px] whitespace-nowrap">
        
        <div class="bg-white dark:bg-[#1e1e2d] text-gray-800 dark:text-gray-200 rounded-xl shadow-xl border border-gray-250/50 dark:border-[#2d2d3a] overflow-hidden">
            {{-- Parent Name Header --}}
            <div class="px-4 py-2 bg-gray-50 dark:bg-[#252535] border-b border-gray-150 dark:border-[#2d2d3a] font-bold text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400" x-text="name">
            </div>
            
            {{-- Children Links --}}
            <div class="py-1 px-1.5 space-y-0.5">
                <template x-for="child in children" :key="child.name">
                    <a :href="child.route" wire:navigate
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-150"
                        :class="child.isActive
                            ? 'bg-blue-50 dark:bg-blue-600/20 text-blue-700 dark:text-blue-300 font-semibold'
                            : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-800 dark:hover:text-white'">
                        
                        {{-- Icon --}}
                        <div class="shrink-0 text-current opacity-80">
                            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                        </div>
                        
                        <span x-text="child.name"></span>
                    </a>
                </template>
            </div>
        </div>
    </div>

    {{-- Mobile sidebar backdrop --}}
    <div x-show="sidebarOpen && window.innerWidth < 1024"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm lg:hidden">
    </div>

    {{-- =====================
         MAIN WRAPPER
         ===================== --}}
    <div class="min-h-screen transition-all duration-300"
        :class="sidebarOpen ? (sidebarCollapsed ? 'lg:ml-16' : 'lg:ml-64') : 'lg:ml-0'">

        {{-- =====================
             HEADER / NAVBAR
             ===================== --}}
        <nav class="fixed top-0 right-0 left-0 z-30 bg-[#121a35] border-b border-[#1d2747] transition-all duration-300"
            :style="sidebarOpen ? (sidebarCollapsed ? 'padding-left: 4rem' : 'padding-left: 16rem') : 'padding-left: 0'"
            style="padding-left: 0">
            <div class="px-4 h-16 flex items-center gap-x-3">

                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Spacer --}}
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

        {{-- =====================
             MAIN CONTENT
             ===================== --}}
        <main class="pt-16 min-h-screen flex flex-col">
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
                            v1.0.0
                        </span>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    @include('partials.toast-notifications')

    <x-ui.modal name="logout-modal" title="Konfirmasi Logout" maxWidth="md">
        <div class="text-center py-4">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 mb-6">
                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-base">Apakah Anda yakin ingin keluar dari aplikasi?</p>
        </div>

        <x-slot name="footer">
            <button type="button" x-on:click="show = false"
                class="flex-1 inline-flex justify-center rounded-lg px-4 py-2.5 bg-gray-100 dark:bg-gray-800 text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                Batal
            </button>
            <a href="{{ route('logout') }}"
                class="flex-1 inline-flex justify-center items-center rounded-lg px-4 py-2.5 bg-red-600 text-sm font-bold text-white hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">
                Ya, Logout
            </a>
        </x-slot>
    </x-ui.modal>

    <x-ui.delete-confirm-modal />

    <script>
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
