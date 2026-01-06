{{-- Navigation with Transparent Overlay & Sticky on Scroll --}}
<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 100)"
     class="z-50 transition-all duration-300"
     :class="{ 
         'fixed top-0 left-0 right-0 bg-white dark:bg-dark shadow-lg': scrolled, 
         'absolute top-0 left-0 right-0 bg-transparent': !scrolled 
     }">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-20">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2">
                @if($aboutUs?->logo)
                    <img src="{{ Storage::url($aboutUs->logo) }}" alt="{{ $aboutUs->company_name }}" class="h-8" :class="{ 'brightness-0 dark:brightness-100 dark:invert': scrolled, 'brightness-0 invert': !scrolled }">
                @else
                    <div class="w-7 h-7 rounded flex items-center justify-center" :class="{ 'bg-primary': scrolled, 'bg-white': !scrolled }">
                        <svg class="w-4 h-4" :class="{ 'text-white': scrolled, 'text-slate-900': !scrolled }" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    </div>
                @endif
                <span class="font-bold text-xl transition-colors" :class="{ 'text-slate-900 dark:text-white': scrolled, 'text-white': !scrolled }">{{ $aboutUs->company_name ?? config('app.name') }}</span>
            </a>

            {{-- Desktop Navigation (Right Aligned) --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="/" class="text-sm font-medium transition-colors relative pb-1 {{ request()->is('/') ? 'border-b-2 border-white' : '' }}" 
                   :class="{ 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white {{ request()->is('/') ? '!border-primary' : '' }}': scrolled, 'text-white/80 hover:text-white': !scrolled }">
                    Home
                </a>
                <a href="{{ route('news.index') }}" class="text-sm font-medium transition-colors relative pb-1 {{ request()->is('news*') ? 'border-b-2 border-white' : '' }}" 
                   :class="{ 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white {{ request()->is('news*') ? '!border-primary' : '' }}': scrolled, 'text-white/80 hover:text-white': !scrolled }">
                    News
                </a>
                <a href="{{ route('about') }}" class="text-sm font-medium transition-colors relative pb-1 {{ request()->is('about*') ? 'border-b-2 border-white' : '' }}" 
                   :class="{ 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white {{ request()->is('about*') ? '!border-primary' : '' }}': scrolled, 'text-white/80 hover:text-white': !scrolled }">
                    About
                </a>
                <a href="{{ route('about') }}#contact" class="text-sm font-medium transition-colors" 
                   :class="{ 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white': scrolled, 'text-white/80 hover:text-white': !scrolled }">
                    Contact
                </a>
                
                {{-- Auth Buttons --}}
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg transition-all"
                       :class="{ 'text-white bg-primary hover:bg-teal-700': scrolled, 'text-slate-900 bg-white hover:bg-slate-100': !scrolled }">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('auth.login') }}" class="text-sm font-medium transition-colors" 
                       :class="{ 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white': scrolled, 'text-white/80 hover:text-white': !scrolled }">
                        Login
                    </a>
                    <a href="{{ route('auth.register') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg transition-all"
                       :class="{ 'text-white bg-accent hover:bg-orange-600 shadow-lg shadow-orange-500/30': scrolled, 'text-slate-900 bg-white hover:bg-slate-100': !scrolled }">
                        Sign Up
                    </a>
                @endauth

                {{-- Dark Mode Toggle --}}
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg transition-colors"
                        :class="{ 'hover:bg-slate-100 dark:hover:bg-dark-card text-slate-600 dark:text-slate-300': scrolled, 'hover:bg-white/10 text-white/80': !scrolled }">
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile: Theme Toggle & Menu Button --}}
            <div class="flex items-center gap-2 lg:hidden">
                {{-- Dark Mode Toggle (Mobile) --}}
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg transition-colors"
                        :class="{ 'hover:bg-slate-100 dark:hover:bg-dark-card text-slate-600 dark:text-slate-300': scrolled, 'hover:bg-white/10 text-white': !scrolled }">
                    <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
                
                {{-- Mobile Menu Button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg transition-colors"
                        :class="{ 'hover:bg-slate-100 dark:hover:bg-dark-card': scrolled, 'hover:bg-white/10': !scrolled }">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6 transition-colors" :class="{ 'text-slate-600 dark:text-slate-300': scrolled, 'text-white': !scrolled }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 transition-colors" :class="{ 'text-slate-600 dark:text-slate-300': scrolled, 'text-white': !scrolled }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation Menu --}}
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-2" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 translate-y-0" 
             x-transition:leave-end="opacity-0 -translate-y-2" 
             class="lg:hidden py-4 bg-white dark:bg-dark rounded-xl shadow-xl mb-4">
            <div class="flex flex-col gap-1 px-2">
                <a href="/" @click="mobileMenuOpen = false" class="px-4 py-3 {{ request()->is('/') ? 'text-primary bg-teal-50 dark:bg-teal-900/20' : 'text-slate-600 dark:text-slate-300' }} hover:bg-slate-50 dark:hover:bg-dark-card rounded-lg font-medium">Home</a>
                <a href="{{ route('news.index') }}" @click="mobileMenuOpen = false" class="px-4 py-3 {{ request()->is('news*') ? 'text-primary bg-teal-50 dark:bg-teal-900/20' : 'text-slate-600 dark:text-slate-300' }} hover:bg-slate-50 dark:hover:bg-dark-card rounded-lg font-medium">News</a>
                <a href="{{ route('about') }}" @click="mobileMenuOpen = false" class="px-4 py-3 {{ request()->is('about*') ? 'text-primary bg-teal-50 dark:bg-teal-900/20' : 'text-slate-600 dark:text-slate-300' }} hover:bg-slate-50 dark:hover:bg-dark-card rounded-lg font-medium">About</a>
                <a href="{{ route('about') }}#contact" @click="mobileMenuOpen = false" class="px-4 py-3 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-dark-card rounded-lg font-medium">Contact</a>
                
                <div class="border-t border-slate-100 dark:border-dark-border mt-2 pt-4 px-2 space-y-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block w-full text-center px-4 py-3 text-sm font-semibold text-white bg-primary rounded-lg">Dashboard</a>
                    @else
                        <a href="{{ route('auth.login') }}" class="block w-full text-center px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-dark-border rounded-lg">Login</a>
                        <a href="{{ route('auth.register') }}" class="block w-full text-center px-4 py-3 text-sm font-semibold text-white bg-accent rounded-lg shadow-lg shadow-orange-500/30">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>