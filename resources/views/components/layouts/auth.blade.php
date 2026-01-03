<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Theme Initialization -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Styles & Scripts -->
    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- Logo -->
            <div class="flex flex-col items-center justify-center">
                <div
                    class="flex items-center justify-center w-16 h-16 rounded-2xl bg-linear-to-br from-indigo-500 to-purple-600 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                    {{ config('app.name', 'Laravel') }}
                </h2>
            </div>

            <!-- Content -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700 p-8">
                {{ $slot }}
            </div>
        </div>
    </div>

    @fluxScripts
    @filamentScripts
</body>

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
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
</body>

</html>