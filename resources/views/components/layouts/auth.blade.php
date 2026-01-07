<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    {{-- Immediate theme application to prevent FOUC (Flash of Unstyled Content) --}}
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Alpine Theme Store - for theme state management -->
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
                    Alpine.effect(() => {
                        const isDark = this.value === 'dark';
                        document.documentElement.classList.toggle('dark', isDark);
                        localStorage.setItem('theme', this.value);
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

        // Ensure store exists and is synced after Livewire navigation
        document.addEventListener('livewire:navigated', () => {
            const storedTheme = localStorage.getItem('theme') || 'light';

            if (!Alpine.store('theme')) {
                Alpine.store('theme', {
                    value: storedTheme,
                    get isDark() {
                        return this.value === 'dark';
                    },
                    toggle() {
                        this.value = this.isDark ? 'light' : 'dark';
                        const isDark = this.value === 'dark';
                        document.documentElement.classList.toggle('dark', isDark);
                        localStorage.setItem('theme', this.value);
                    }
                });
            } else {
                // Sync store with localStorage
                if (Alpine.store('theme').value !== storedTheme) {
                    Alpine.store('theme').value = storedTheme;
                }
            }

            // Always ensure DOM class is correct
            const isDark = storedTheme === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
        });
    </script>

    <!-- Styles & Scripts -->
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Auth button styling */
        .auth-btn-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            border-radius: 0.75rem !important;
            padding: 0.875rem 1.5rem !important;
            font-size: 0.9375rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 14px -3px rgba(249, 115, 22, 0.5) !important;
            border: none !important;
        }

        .auth-btn-primary:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%) !important;
            box-shadow: 0 6px 20px -3px rgba(249, 115, 22, 0.6) !important;
            transform: translateY(-1px) !important;
        }

        .auth-btn-primary:active {
            transform: translateY(0) !important;
        }

        /* Input styling - Light Mode */
        .auth-form input:not([type="checkbox"]):not([type="radio"]) {
            background-color: rgba(241, 245, 249, 0.9) !important;
            border: 1px solid rgba(203, 213, 225, 0.8) !important;
            color: #1e293b !important;
            border-radius: 0.75rem !important;
            padding: 0.875rem 1rem !important;
            font-size: 0.9375rem !important;
            transition: all 0.2s ease !important;
        }

        .auth-form input:not([type="checkbox"]):not([type="radio"]):focus {
            border-color: #0d9488 !important;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15) !important;
            background-color: #ffffff !important;
        }

        .auth-form input:not([type="checkbox"]):not([type="radio"])::placeholder {
            color: rgba(100, 116, 139, 0.7) !important;
        }

        /* Input styling - Dark Mode */
        .dark .auth-form input:not([type="checkbox"]):not([type="radio"]) {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border: 1px solid rgba(71, 85, 105, 0.5) !important;
            color: #f1f5f9 !important;
        }

        .dark .auth-form input:not([type="checkbox"]):not([type="radio"]):focus {
            border-color: #0d9488 !important;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.2) !important;
            background-color: rgba(30, 41, 59, 0.9) !important;
        }

        .dark .auth-form input:not([type="checkbox"]):not([type="radio"])::placeholder {
            color: rgba(148, 163, 184, 0.6) !important;
        }
    </style>
</head>

<body
    class="min-h-screen antialiased font-sans transition-colors duration-500 bg-gradient-to-br from-slate-100 via-slate-50 to-slate-200 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    {{-- Background Decorative Elements --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        {{-- Decorative Orb 1 - Teal --}}
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-20 dark:opacity-10 transition-opacity duration-500"
            style="background: radial-gradient(circle, rgba(13, 148, 136, 0.4) 0%, transparent 70%); filter: blur(60px);">
        </div>
        {{-- Decorative Orb 2 - Orange --}}
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full opacity-15 dark:opacity-10 transition-opacity duration-500"
            style="background: radial-gradient(circle, rgba(249, 115, 22, 0.3) 0%, transparent 70%); filter: blur(60px);">
        </div>
        {{-- Subtle grid pattern --}}
        <div class="absolute inset-0 opacity-[0.02] dark:opacity-[0.03]"
            style="background-image: linear-gradient(rgba(100, 116, 139, 0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(100, 116, 139, 0.5) 1px, transparent 1px); background-size: 50px 50px;">
        </div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8" x-data="{
            get isDark() {
                return $store.theme ? $store.theme.isDark : (localStorage.getItem('theme') === 'dark');
            },
            toggle() {
                if ($store.theme) {
                    $store.theme.toggle();
                } else {
                    const newTheme = this.isDark ? 'light' : 'dark';
                    localStorage.setItem('theme', newTheme);
                    document.documentElement.classList.toggle('dark', newTheme === 'dark');
                }
            }
        }">
        <div class="w-full max-w-md space-y-8">
            {{-- Logo --}}
            <div class="flex flex-col items-center justify-center">
                {{-- Logo Icon with Gradient --}}
                <div class="relative group">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl blur-xl opacity-40 group-hover:opacity-60 transition-opacity duration-500">
                    </div>
                    <div
                        class="relative flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 shadow-lg shadow-teal-500/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                {{-- App Name --}}
                <h1
                    class="mt-6 text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white transition-colors duration-300">
                    {{ config('app.name', 'Laravel') }}
                </h1>
            </div>

            {{-- Glass Card Container --}}
            <div class="auth-form rounded-3xl p-8 sm:p-10 transition-all duration-500
                bg-white/80 dark:bg-slate-800/70 
                backdrop-blur-2xl 
                border border-white/60 dark:border-slate-700/50 
                shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50">

                {{-- Theme Toggle Inside Card --}}
                <div class="flex justify-end mb-6 -mt-2 -mr-2">
                    <button @click="toggle()" class="group relative p-2.5 rounded-xl transition-all duration-300 hover:scale-105 active:scale-95
                            bg-slate-100 dark:bg-slate-700/80 
                            border border-slate-200 dark:border-slate-600/50 
                            hover:bg-slate-200 dark:hover:bg-slate-600
                            shadow-sm hover:shadow-md"
                        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
                        {{-- Sun Icon (shown in dark mode) --}}
                        <svg x-show="isDark" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 rotate-90 scale-0"
                            x-transition:enter-end="opacity-100 rotate-0 scale-100" class="w-5 h-5 text-amber-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{-- Moon Icon (shown in light mode) --}}
                        <svg x-show="!isDark" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -rotate-90 scale-0"
                            x-transition:enter-end="opacity-100 rotate-0 scale-100" class="w-5 h-5 text-slate-500"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

                {{ $slot }}
            </div>

            {{-- Footer Link --}}
            <div class="text-center">
                <a href="/"
                    class="text-sm text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition-colors duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>

    @fluxScripts
    @filamentScripts

    {{-- Toast Notifications --}}
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
                    class="pointer-events-auto w-[350px] p-4 rounded-xl shadow-2xl border-l-4 flex items-center justify-between gap-4 bg-white dark:bg-slate-900/90 backdrop-blur-xl text-slate-800 dark:text-white"
                    :class="{ 'border-green-500': toast.variant === 'success', 'border-red-500': toast.variant === 'danger' }">
                    <div class="flex items-center gap-3">
                        <template x-if="toast.variant === 'success'">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                        <template x-if="toast.variant === 'danger'">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                        <span class="text-sm font-semibold" x-text="toast.text"></span>
                    </div>
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
                </div>
            </template>
        </div>
    </div>
</body>

</html>