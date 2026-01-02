<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

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

    <script data-navigate-once>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                value: localStorage.getItem('theme') || 'light',
                
                init() {
                    Alpine.effect(() => {
                        const isDark = this.value === 'dark';
                        document.documentElement.classList.toggle('dark', isDark);
                        if (window.$flux) window.$flux.appearance = this.value;
                        localStorage.setItem('theme', this.value);
                    });

                    document.addEventListener('livewire:navigated', () => {
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
        });
    </script>
</head>

<body class="min-h-screen antialiased bg-zinc-50 dark:bg-[#1b1c22] text-zinc-900 dark:text-white">
    <flux:sidebar sticky stashable class="w-[300px] bg-zinc-900 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800 text-white">
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
                    <flux:navlist.item :icon="$menu['icon'] ?? 'square-2-stack'" :href="$menu['route'] ? route($menu['route']) : '#'" :current="request()->routeIs($menu['route'] ?? '')">
                        {{ $menu['name'] }}
                    </flux:navlist.item>
                @else
                    <flux:navlist.group :heading="$menu['name']" :icon="$menu['icon'] ?? 'square-2-plus'" expandable :expanded="collect($menu['children'])->contains('route', request()->route()?->getName())">
                        @foreach($menu['children'] as $child)
                            <flux:navlist.item :icon="$child['icon'] ?? 'minus'" :href="$child['route'] ? route($child['route']) : '#'" :current="request()->routeIs($child['route'] ?? '')">
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

        <div class="flex items-center max-lg:hidden" x-data="{ sidebarOpen: true }">
            <button type="button" 
                    @click="$dispatch('flux-stash-sidebar'); sidebarOpen = !sidebarOpen"
                    class="flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all">
                <template x-if="sidebarOpen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </template>
                <template x-if="!sidebarOpen">
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

        <flux:navbar class="mr-4">
            <flux:navbar.item icon="magnifying-glass" href="#" label="Search" />
            <flux:navbar.item icon="bell" href="#" label="Notifications" />
        </flux:navbar>

        <flux:dropdown position="top" align="start">
            <flux:profile class="cursor-pointer" 
                :avatar="auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : null"
                initials="{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}" />

            <flux:menu>
                <flux:menu.item icon="user-circle" href="{{ route('profile') }}">Profile</flux:menu.item>
                <flux:menu.item icon="key" href="{{ route('password.change') }}">Change Password</flux:menu.item>

                <flux:separator />

                <flux:menu.submenu icon="shield-check" heading="Switch Role">
                    @foreach(auth()->user()->roles as $role)
                        <flux:menu.item href="{{ route('roles.switch', $role->id) }}" :icon="auth()->user()->role_id == $role->id ? 'check' : ''">
                            {{ $role->name }}
                        </flux:menu.item>
                    @endforeach
                </flux:menu.submenu>

                <flux:separator />

                <div x-data x-on:mousedown.stop x-on:click.stop x-on:mouseup.stop x-on:keydown.stop
                    class="flex items-center justify-between px-3 py-2 outline-hidden">
                    <div class="flex items-center gap-2">
                        <div x-show="!$store.theme.isDark" class="flex items-center">
                            <flux:icon name="sun" size="sm" class="text-zinc-400" />
                        </div>
                        <div x-show="$store.theme.isDark" class="flex items-center" x-cloak>
                            <flux:icon name="moon" size="sm" class="text-zinc-400" />
                        </div>
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400" x-text="$store.theme.isDark ? 'Dark Mode' : 'Light Mode'"></span>
                    </div>

                    <flux:switch x-on:click.prevent.stop="$store.theme.toggle()" x-model="$store.theme.isDark" size="sm" />
                </div>

                <flux:separator />

                <flux:modal.trigger name="logout-modal">
                    <flux:menu.item icon="arrow-right-start-on-rectangle" variant="danger">
                        Logout
                    </flux:menu.item>
                </flux:modal.trigger>
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
                    x-transition:enter-start="-translate-y-4 opacity-0 scale-95"
                    x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="pointer-events-auto w-[350px] p-4 rounded-lg shadow-2xl border-l-4 flex items-center justify-between gap-4 bg-zinc-900 text-white"
                    :class="{ 'border-green-500': toast.variant === 'success', 'border-red-500': toast.variant === 'danger' }">
                    <div class="flex items-center gap-3">
                        <template x-if="toast.variant === 'success'">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                        <template x-if="toast.variant === 'danger'">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                        <span class="text-sm font-semibold" x-text="toast.text"></span>
                    </div>
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                        class="text-white/50 hover:text-white">&times;</button>
                </div>
            </template>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <flux:modal name="logout-modal" class="max-w-md z-100">
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                <flux:icon name="arrow-right-start-on-rectangle" class="h-7 w-7 text-red-600 dark:text-red-400" />
            </div>

            <flux:heading size="lg">Konfirmasi Logout</flux:heading>
            <flux:subheading>Apakah Anda yakin ingin keluar dari aplikasi?</flux:subheading>
        </div>

        <div class="flex gap-3 mt-6">
            <flux:modal.close>
                <flux:button variant="ghost" class="flex-1">Batal</flux:button>
            </flux:modal.close>
            <flux:button href="{{ route('logout') }}" variant="danger" class="flex-1">Ya, Logout</flux:button>
        </div>
    </flux:modal>

    @fluxScripts
    @filamentScripts
</body>

</html>