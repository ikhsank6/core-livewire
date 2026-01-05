<!-- Top Bar (Hidden on mobile) -->
<div class="bg-dark text-white text-sm hidden md:block">
    <div class="container mx-auto px-4 py-2 flex flex-wrap justify-between items-center">
        <div class="flex items-center gap-6 flex-wrap">
            @if($aboutUs?->phone)
                <a href="tel:{{ $aboutUs->phone }}" class="flex items-center gap-2 hover:text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ $aboutUs->phone }}
                </a>
            @endif
            @if($aboutUs?->email)
                <a href="mailto:{{ $aboutUs->email }}" class="flex items-center gap-2 hover:text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ $aboutUs->email }}
                </a>
            @endif
            @if($aboutUs?->address)
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                    </svg>
                    {{ Str::limit($aboutUs->address, 50) }}
                </span>
            @endif
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('auth.login') }}"
                class="bg-primary hover:bg-red-600 text-white px-4 py-1.5 rounded text-xs font-semibold">Login</a>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="/" class="flex items-center gap-2">
            @if($aboutUs?->logo)
                <img src="{{ Storage::url($aboutUs->logo) }}" alt="{{ $aboutUs->company_name }}" class="h-8 md:h-10">
            @else
                <div class="w-8 h-8 md:w-10 md:h-10 bg-primary rounded flex items-center justify-center">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z" />
                    </svg>
                </div>
            @endif
            <span class="text-lg md:text-2xl font-bold text-dark">{{ $aboutUs->company_name ?? 'COMPANY' }}</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden lg:flex items-center gap-8">
            <a href="/"
                class="{{ request()->is('/') ? 'text-primary font-semibold' : 'text-gray-700 hover:text-primary' }}">Home</a>
            <a href="{{ route('news.index') }}"
                class="{{ request()->is('news*') ? 'text-primary font-semibold' : 'text-gray-700 hover:text-primary' }}">News</a>
            <a href="{{ route('about') }}"
                class="{{ request()->is('about*') ? 'text-primary font-semibold' : 'text-gray-700 hover:text-primary' }}">About
                Us</a>
        </div>

        <!-- Desktop CTA & Mobile Hamburger -->
        <div class="flex items-center gap-4">
            <a href="{{ route('about') }}#contact"
                class="hidden md:flex bg-primary hover:bg-red-600 text-white px-4 lg:px-6 py-2 lg:py-3 font-semibold items-center gap-2 transition-all text-sm lg:text-base">
                GET A QUOTE
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <!-- Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-gray-700 hover:text-primary">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Drawer -->
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden bg-white border-t border-gray-100 shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col space-y-1">
                <a href="/"
                    class="py-3 px-4 {{ request()->is('/') ? 'text-primary' : 'text-gray-700' }} font-semibold border-b border-gray-100">Home</a>
                <a href="{{ route('news.index') }}"
                    class="py-3 px-4 {{ request()->is('news*') ? 'text-primary' : 'text-gray-700' }} font-semibold border-b border-gray-100">News</a>
                <a href="{{ route('about') }}"
                    class="py-3 px-4 {{ request()->is('about*') ? 'text-primary' : 'text-gray-700' }} font-semibold border-b border-gray-100">About
                    Us</a>
                <div class="pt-4 space-y-3">
                    <a href="{{ route('about') }}#contact"
                        class="block w-full bg-primary hover:bg-red-600 text-white py-3 text-center font-semibold rounded-lg">Get
                        A Quote</a>
                    <a href="{{ route('auth.login') }}"
                        class="block w-full border border-primary text-primary py-3 text-center font-semibold rounded-lg hover:bg-primary hover:text-white transition-colors">Login</a>
                </div>
            </div>
        </div>
    </div>
</nav>