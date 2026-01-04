<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="antialiased bg-white text-zinc-900">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-zinc-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">M</span>
                    </div>
                    <span class="font-bold text-xl text-zinc-900">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home"
                        class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Beranda</a>
                    <a href="#news"
                        class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Berita</a>
                    <a href="#about"
                        class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Tentang Kami</a>
                    <a href="#contact"
                        class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">Kontak</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:opacity-90 transition-opacity shadow-lg shadow-indigo-500/25">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('auth.login') }}"
                            class="px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('auth.register') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:opacity-90 transition-opacity shadow-lg shadow-indigo-500/25">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">M</span>
                        </div>
                        <span class="font-bold text-2xl">{{ config('app.name', 'Laravel') }}</span>
                    </div>
                    <p class="text-zinc-400 max-w-md">
                        Solusi terbaik untuk kebutuhan bisnis Anda. Kami berkomitmen memberikan layanan terbaik dengan
                        teknologi modern.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-zinc-400 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#news" class="text-zinc-400 hover:text-white transition-colors">Berita</a></li>
                        <li><a href="#about" class="text-zinc-400 hover:text-white transition-colors">Tentang Kami</a>
                        </li>
                        <li><a href="#contact" class="text-zinc-400 hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-semibold text-lg mb-4">Kontak</h4>
                    <ul class="space-y-2 text-zinc-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            info@example.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            +62 123 456 789
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-zinc-800 mt-12 pt-8 text-center text-zinc-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>