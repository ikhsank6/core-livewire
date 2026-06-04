{{--
    Props:
    $title      (string)  – heading text
    $breadcrumb (string)  – current page label in breadcrumb
    $subtitle   (string, optional)
    $bgImage    (string|null, optional) – URL to overlay image
--}}
<section class="relative overflow-hidden" style="background: linear-gradient(135deg, #0d1117 0%, #161b25 50%, #0a1628 100%);">

    {{-- Abstract decorative shapes (Aveit-style) --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        {{-- Large primary orb --}}
        <div class="shape-blob w-96 h-96 bg-primary"
             style="top: -80px; right: 10%; opacity: 0.12;"></div>
        {{-- Accent orb --}}
        <div class="shape-blob w-64 h-64 bg-accent"
             style="bottom: -40px; left: 5%; opacity: 0.08;"></div>
        {{-- Grid dots pattern --}}
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 32px 32px;"></div>
        {{-- Optional bg image --}}
        @if(!empty($bgImage))
            <div class="absolute inset-0 opacity-10"
                 style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center;"></div>
        @endif
    </div>

    {{-- Content --}}
    <div class="relative z-10 pt-36 pb-24 md:pt-44 md:pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">

            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="flex items-center justify-center gap-2 text-sm mb-6">
                <a href="/" class="text-white/50 hover:text-white/80 transition-colors flex items-center gap-1.5 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <svg class="w-3 h-3 text-white/25" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-primary font-semibold">{{ $breadcrumb }}</span>
            </nav>

            {{-- Title --}}
            <h1 class="font-heading font-extrabold text-white text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight">
                {{ $title }}
            </h1>

            @if(!empty($subtitle))
                <p class="mt-5 text-lg text-white/55 max-w-2xl mx-auto leading-relaxed">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    {{-- Curved bottom wave --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg class="w-full h-12 md:h-20 block" viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 80V40C240 70 480 80 720 60C960 40 1200 20 1440 10V80H0Z"
                  class="fill-white dark:fill-dark"/>
        </svg>
    </div>
</section>
