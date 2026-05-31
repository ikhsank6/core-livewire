{{--
    Props:
    $title      (string)  – heading text
    $breadcrumb (string)  – current page label in breadcrumb
    $subtitle   (string, optional)
    $bgImage    (string|null, optional) – URL to overlay image
--}}
<section class="relative bg-dark overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-linear-to-br from-slate-950 via-slate-900/95 to-primary-dark/80"></div>
        @if(!empty($bgImage))
            <div class="absolute inset-0 opacity-15"
                 style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center;"></div>
        @endif
        {{-- Subtle orbs --}}
        <div class="absolute top-0 right-1/4 w-80 h-80 bg-primary/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-1/4 w-64 h-64 bg-accent/8 rounded-full blur-[80px]"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 pt-32 pb-20 md:pt-40 md:pb-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="flex items-center justify-center gap-2 text-sm mb-5">
                <a href="/" class="text-white/60 hover:text-white transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <svg class="w-3.5 h-3.5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-primary font-medium">{{ $breadcrumb }}</span>
            </nav>

            {{-- Title --}}
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-white">
                {{ $title }}
            </h1>

            @if(!empty($subtitle))
                <p class="mt-4 text-lg text-white/60 max-w-2xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    {{-- Curved bottom --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg class="w-full h-12 md:h-20" viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 80V40C360 80 720 80 1080 40C1260 20 1380 10 1440 0V80H0Z"
                  class="fill-slate-50 dark:fill-dark"/>
        </svg>
    </div>
</section>
