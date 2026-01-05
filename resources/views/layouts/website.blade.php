<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $aboutUs->company_name ?? config('app.name'))</title>
    <meta name="description" content="@yield('description', '')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @hasSection('swiper')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endif
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E84C3D',
                        dark: '#1a1a2e',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .hero-slide {
            background-size: cover;
            background-position: center;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: white !important;
        }

        @media (max-width: 640px) {

            .swiper-button-next,
            .swiper-button-prev {
                display: none !important;
            }
        }

        .swiper-pagination-bullet-active {
            background: #E84C3D !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prose {
            max-width: none;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 1.5rem 0 1rem;
        }

        .prose h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 1.25rem 0 0.75rem;
        }

        .prose ul,
        .prose ol {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose a {
            color: #E84C3D;
        }

        .prose img {
            border-radius: 0.5rem;
            margin: 1rem 0;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    {{-- Navigation --}}
    @include('website.partials.nav')

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('website.partials.footer')

    {{-- Swiper JS (only if needed) --}}
    @hasSection('swiper')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @endif

    @stack('scripts')
</body>

</html>