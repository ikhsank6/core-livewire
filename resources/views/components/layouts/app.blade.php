<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
{{-- Immediate theme application to prevent FOUC (Flash of Unstyled Content) --}}
<script>
    (function () {
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Global Modal Backdrop Blur */
        [data-flux-modal-backdrop] {
            backdrop-filter: blur(8px) !important;
            background-color: rgba(27, 28, 34, 0.8) !important;
        }
    </style>

    <script data-navigate-once>
        // Apply theme immediately on every navigation (before Alpine loads)
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                value: localStorage.getItem('theme') || 'light',

                init() {
                    // Ensure dark class is set on init
                    document.documentElement.classList.toggle('dark', this.value === 'dark');

                    Alpine.effect(() => {
                        const isDark = this.value === 'dark';
                        document.documentElement.classList.toggle('dark', isDark);
                        if (window.$flux) window.$flux.appearance = this.value;
                        localStorage.setItem('theme', this.value);
                    });

                    // Re-apply on Livewire navigation
                    document.addEventListener('livewire:navigating', () => {
                        // Set immediately before navigation starts
                        document.documentElement.classList.toggle('dark', this.value === 'dark');
                    });

                    document.addEventListener('livewire:navigated', () => {
                        // Ensure it's still applied after navigation completes
                        document.documentElement.classList.toggle('dark', this.value === 'dark');
                    });
                },

                get isDark() {
                    return this.value === 'dark';
                },

                toggle() {
                    this.value = this.isDark ? 'light' : 'dark';
                }
            });

            // Sidebar state store
            Alpine.store('sidebarState', {
                open: true,
                toggle() {
                    this.open = !this.open;
                }
            });
        });

        // Ensure stores exist after Livewire navigation
        document.addEventListener('livewire:navigated', () => {
            // Re-initialize stores if they don't exist
            if (!Alpine.store('theme')) {
                Alpine.store('theme', {
                    value: localStorage.getItem('theme') || 'light',
                    get isDark() {
                        return this.value === 'dark';
                    },
                    toggle() {
                        this.value = this.isDark ? 'light' : 'dark';
                        document.documentElement.classList.toggle('dark', this.value === 'dark');
                        localStorage.setItem('theme', this.value);
                    }
                });
            }

            if (!Alpine.store('sidebarState')) {
                Alpine.store('sidebarState', {
                    open: true,
                    toggle() {
                        this.open = !this.open;
                    }
                });
            }
        });
    </script>
</head>

<body class="min-h-screen antialiased bg-zinc-50 dark:bg-[#1b1c22] text-zinc-900 dark:text-white"
    x-data="{ sidebarOpen: true }" x-init="if ($store.sidebarState) { sidebarOpen = $store.sidebarState.open }">
    <flux:sidebar sticky stashable x-show="$store.sidebarState ? $store.sidebarState.open : sidebarOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        class="w-[300px] bg-zinc-900 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 text-white">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <div class="h-16 flex items-center px-6 shrink-0">
            <flux:brand href="/" logo="https://fluxui.dev/img/demo/logo.png" name="Metronic" />
        </div>

        <flux:navlist>
            <flux:navlist.item icon="magnifying-glass" href="#">Search</flux:navlist.item>
        </flux:navlist>

        <flux:navlist>
            @php
                $menuService = app(\App\Services\MenuService::class);
                $menuTree = $menuService->getMenuTreeForUser();
            @endphp

            @foreach($menuTree as $menu)
                @if(empty($menu['children']))
                    <flux:navlist.item :icon="$menu['icon'] ?? 'square-2-stack'"
                        :href="$menu['route'] ? route($menu['route']) : '#'"
                        :current="request()->routeIs($menu['route'] ?? '')">
                        {{ $menu['name'] }}
                    </flux:navlist.item>
                @else
                    <flux:navlist.group :heading="$menu['name']" :icon="$menu['icon'] ?? 'square-2-plus'" expandable
                        :expanded="collect($menu['children'])->contains('route', request()->route()?->getName())">
                        @foreach($menu['children'] as $child)
                            <flux:navlist.item :icon="$child['icon'] ?? 'minus'"
                                :href="$child['route'] ? route($child['route']) : '#'"
                                :current="request()->routeIs($child['route'] ?? '')">
                                {{ $child['name'] }}
                            </flux:navlist.item>
                        @endforeach
                    </flux:navlist.group>
                @endif
            @endforeach
        </flux:navlist>

        <flux:spacer />

        <flux:navlist>
            <flux:navlist.item icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
            <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    <flux:header class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left" />

        <div class="flex items-center max-lg:hidden">
            <button type="button"
                @click="$store.sidebarState ? $store.sidebarState.toggle() : (sidebarOpen = !sidebarOpen)"
                class="flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all">
                <template x-if="($store.sidebarState ? $store.sidebarState.open : sidebarOpen)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </template>
                <template x-if="!($store.sidebarState ? $store.sidebarState.open : sidebarOpen)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </template>
            </button>

            @isset($breadcrumbs)
                <flux:separator vertical variant="subtle" class="mx-6 h-5" />

                <flux:breadcrumbs>
                    {{ $breadcrumbs }}
                </flux:breadcrumbs>
            @endisset
        </div>

        <flux:spacer />

        <flux:navbar class="mr-2">
            <livewire:layout.notification-bell />
        </flux:navbar>

        <flux:dropdown position="bottom" align="end">
            <flux:profile class="cursor-pointer"
                :avatar="auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : null"
                initials="{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}" />

            <flux:menu class="w-64!">
                <div class="px-5 py-4 flex items-center gap-4">
                    <!-- Premium Avatar Layout -->
                    <div class="shrink-0">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar"
                                class="h-12 w-12 rounded-2xl object-cover ring-1 ring-zinc-200 dark:ring-zinc-700 shadow-sm">
                        @else
                            <div
                                class="h-12 w-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-sm font-bold text-white uppercase shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Text Info (Left Aligned) -->
                    <div class="flex flex-col text-left leading-none">
                        <span class="text-base font-bold text-zinc-900 dark:text-zinc-100 tracking-tight">
                            {{ auth()->user()->name }}
                        </span>
                        <span
                            class="text-[10px] font-medium text-zinc-500 dark:text-zinc-400 mt-1.5 uppercase tracking-wider">
                            <flux:badge size="sm" color="emerald" icon="shield-check">
                                {{ auth()->user()->role?->name ?? 'No Role' }}
                            </flux:badge>
                        </span>
                    </div>
                </div>

                <flux:separator />

                <flux:menu.item icon="user-circle" href="{{ route('profile') }}">Profile</flux:menu.item>
                <flux:menu.item icon="key" href="{{ route('password.change') }}">Change Password</flux:menu.item>

                <flux:separator />

                <flux:menu.submenu icon="shield-check" heading="Switch Role">
                    @foreach(auth()->user()->roles as $role)
                        <flux:menu.item href="{{ route('roles.switch', $role->id) }}"
                            :icon="auth()->user()->role_id == $role->id ? 'check' : ''">
                            {{ $role->name }}
                        </flux:menu.item>
                    @endforeach
                </flux:menu.submenu>

                <flux:separator />

                <div x-data x-on:mousedown.stop x-on:click.stop x-on:mouseup.stop x-on:keydown.stop
                    class="flex items-center justify-between px-3 py-2 outline-hidden">
                    <div class="flex items-center gap-2">
                        {{-- Sun Icon (Light Mode) --}}
                        <svg x-show="!$store.theme.isDark" class="w-5 h-5 text-zinc-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        {{-- Moon Icon (Dark Mode) --}}
                        <svg x-show="$store.theme.isDark" x-cloak class="w-5 h-5 text-zinc-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>

                        <span class="text-sm font-medium text-zinc-800 dark:text-white"
                            x-text="$store.theme?.isDark ? 'Dark Mode' : 'Light Mode'"></span>
                    </div>

                    <flux:switch x-on:click.prevent.stop="$store.theme?.toggle()"
                        x-bind:checked="$store.theme?.isDark ?? false" />
                </div>

                <flux:separator />

                <div x-on:click="$dispatch('open-modal', { name: 'logout-modal' })">
                    <flux:menu.item icon="arrow-right-start-on-rectangle" variant="danger">
                        Logout
                    </flux:menu.item>
                </div>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <flux:main class="bg-zinc-50 dark:bg-[#1b1c22]">
        <div class="mx-auto max-w-7xl">
            {{ $slot }}
        </div>
    </flux:main>

    <!-- Notification system with wire:ignore to prevent Alpine/Livewire conflicts -->
    <div wire:ignore class="fixed top-5 right-5 pointer-events-none" style="z-index: 999999;">
        <div x-data="{ 
                toasts: [],
                add(data) {
                    const payload = data.detail || data;
                    const text = typeof payload === 'string' ? payload : (payload.text || '');
                    const variant = payload.variant || 'success';
                    
                    if (!text) return;
                    
                    const id = Date.now() + Math.random();
                    this.toasts.push({ id, text, variant });
                    
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 5000);
                }
            }" x-init="
                @if(session('success')) add({ text: '{{ session('success') }}', variant: 'success' }); @endif
                @if(session('error')) add({ text: '{{ session('error') }}', variant: 'danger' }); @endif
            " @notify.window="add($event.detail)" class="flex flex-col gap-3 items-end">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="translate-x-8 opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-8"
                    class="pointer-events-auto w-[380px] rounded-xl shadow-2xl overflow-hidden bg-zinc-900 text-white">

                    {{-- Main Content --}}
                    <div class="p-4 flex items-start gap-3">
                        {{-- Icon --}}
                        <template x-if="toast.variant === 'success'">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </template>
                        <template x-if="toast.variant === 'danger'">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                        </template>

                        {{-- Text --}}
                        <div class="flex-1 pt-0.5">
                            <p class="text-sm font-semibold text-white" x-text="toast.text"></p>
                        </div>

                        {{-- Close Button --}}
                        <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                            class="shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-zinc-400 hover:text-white hover:bg-zinc-700/50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-zinc-500/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="h-1 w-full bg-zinc-800">
                        <div class="h-full animate-shrink-width"
                            :class="toast.variant === 'success' ? 'bg-green-500' : 'bg-red-500'"
                            style="animation-duration: 5s;"></div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <x-ui.modal name="logout-modal" title="Konfirmasi Logout" maxWidth="md">
        <div class="text-center py-4">
            <div
                class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 mb-6">
                <flux:icon name="arrow-right-start-on-rectangle" class="h-8 w-8 text-red-600 dark:text-red-400" />
            </div>

            <p class="text-zinc-600 dark:text-zinc-400 text-base">Apakah Anda yakin ingin keluar dari aplikasi?</p>
        </div>

        <x-slot name="footer">
            <button type="button" x-on:click="show = false"
                class="flex-1 inline-flex justify-center rounded-lg px-4 py-2.5 bg-zinc-100 dark:bg-zinc-800 text-sm font-bold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                Batal
            </button>
            <a href="{{ route('logout') }}"
                class="flex-1 inline-flex justify-center items-center rounded-lg px-4 py-2.5 bg-red-600 text-sm font-bold text-white hover:bg-red-700 transition-colors shadow-lg shadow-red-500/30">
                Ya, Logout
            </a>
        </x-slot>
    </x-ui.modal>

    @fluxScripts
    @filamentScripts
</body>

</html>