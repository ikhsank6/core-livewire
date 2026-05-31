@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' - Beranda')

@section('content')

{{-- ======================== HERO CAROUSEL ======================== --}}
@if($carousels->count() > 0)
<section class="relative overflow-hidden"
    x-data="{
        current: 0,
        total: {{ $carousels->count() }},
        timer: null,
        start() {
            this.timer = setInterval(() => this.next(), 5000);
        },
        next() { this.current = (this.current + 1) % this.total; },
        prev() { this.current = (this.current - 1 + this.total) % this.total; },
        go(i) { this.current = i; clearInterval(this.timer); this.start(); }
    }"
    x-init="start()"
    @mouseenter="clearInterval(timer)"
    @mouseleave="start()">

    {{-- Slides --}}
    @foreach($carousels as $index => $carousel)
    <div x-show="current === {{ $index }}"
         x-transition:enter="transition-opacity duration-700"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="relative min-h-[600px] md:min-h-[680px] flex items-center hero-slide"
         style="background-image: url('{{ $carousel->image ? Storage::url($carousel->image) : '' }}');">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-linear-to-r from-slate-950/90 via-slate-950/70 to-slate-950/40"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 w-full py-24">
            <div class="max-w-2xl">
                @if($carousel->description)
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full text-white/90 text-sm font-medium mb-6">
                        <span class="w-1.5 h-1.5 bg-primary rounded-full"></span>
                        {{ $carousel->description }}
                    </span>
                @endif

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    {{ $carousel->title }}
                </h1>

                @if($carousel->button_text)
                    <a href="{{ $carousel->button_link ?? route('about') }}"
                       class="inline-flex items-center gap-2 px-7 py-3.5 bg-accent hover:bg-accent-dark text-white font-bold rounded-xl shadow-lg shadow-orange-500/25 transition-all duration-200 hover:scale-[1.03] active:scale-[0.98]">
                        {{ $carousel->button_text }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    {{-- Prev / Next --}}
    @if($carousels->count() > 1)
    <button @click="prev()" aria-label="Slide sebelumnya"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white flex items-center justify-center transition-all duration-200 focus-visible:ring-2 focus-visible:ring-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button @click="next()" aria-label="Slide berikutnya"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white flex items-center justify-center transition-all duration-200 focus-visible:ring-2 focus-visible:ring-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2" role="tablist" aria-label="Slide navigation">
        @foreach($carousels as $i => $c)
        <button @click="go({{ $i }})"
                :class="current === {{ $i }} ? 'w-8 bg-primary' : 'w-2.5 bg-white/40 hover:bg-white/70'"
                class="h-2.5 rounded-full transition-all duration-300"
                role="tab"
                :aria-selected="current === {{ $i }}"
                aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif

    {{-- Scroll hint --}}
    <div class="absolute bottom-6 right-6 z-20 hidden md:flex flex-col items-center gap-1.5 cursor-pointer opacity-60 hover:opacity-100 transition-opacity"
         onclick="window.scrollBy({top: window.innerHeight, behavior:'smooth'})">
        <span class="text-[10px] font-semibold tracking-[.25em] uppercase text-white">Scroll</span>
        <div class="w-5 h-8 rounded-full border border-white/40 flex justify-center pt-1.5">
            <div class="w-1 h-2 bg-white rounded-full animate-scroll-dot"></div>
        </div>
    </div>
</section>

@else
{{-- Fallback hero bila carousel kosong --}}
<section class="relative min-h-[520px] flex items-center bg-linear-to-br from-slate-950 via-slate-900 to-primary-dark/60 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-accent/8 rounded-full blur-[90px]"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-24 text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
            {{ $aboutUs->company_name ?? config('app.name') }}
        </h1>
        @if($aboutUs?->description)
            <p class="text-lg text-white/70 max-w-2xl mx-auto mb-8">
                {{ Str::limit(strip_tags($aboutUs->description), 180) }}
            </p>
        @endif
        <a href="{{ route('about') }}"
           class="inline-flex items-center gap-2 px-7 py-3.5 bg-accent hover:bg-accent-dark text-white font-bold rounded-xl shadow-lg shadow-orange-500/25 transition-all duration-200 hover:scale-[1.03]">
            Tentang Kami
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>
@endif


{{-- ======================== ABOUT RINGKAS ======================== --}}
@if($aboutUs)
<section id="tentang" class="py-20 bg-white dark:bg-dark">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            {{-- Logo / visual --}}
            <div class="flex justify-center">
                @if($aboutUs->logo)
                    <div class="bg-slate-50 dark:bg-dark-card border border-slate-100 dark:border-dark-border rounded-2xl p-10 flex items-center justify-center max-w-sm w-full">
                        <img src="{{ Storage::url($aboutUs->logo) }}"
                             alt="{{ $aboutUs->company_name }}"
                             class="max-h-48 max-w-full object-contain dark:brightness-0 dark:invert">
                    </div>
                @else
                    <div class="w-64 h-64 bg-linear-to-br from-primary/15 to-accent/10 rounded-3xl flex items-center justify-center">
                        <div class="w-20 h-20 bg-primary rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/>
                            </svg>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Text --}}
            <div>
                @include('website.partials.section-heading', [
                    'eyebrow' => 'Tentang Kami',
                    'title'   => $aboutUs->company_name ?? 'Tentang Perusahaan',
                    'align'   => 'left',
                ])
                @if($aboutUs->description)
                    <div class="prose dark:prose-invert text-slate-600 dark:text-slate-300 mb-8">
                        {!! Str::limit(strip_tags($aboutUs->description), 320) !!}
                    </div>
                @endif
                <a href="{{ route('about') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 border-2 border-primary text-primary hover:bg-primary hover:text-white font-semibold rounded-xl transition-all duration-200">
                    Selengkapnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endif


{{-- ======================== BERITA TERBARU ======================== --}}
@if($news->count() > 0)
<section class="py-20 bg-slate-50 dark:bg-dark-card">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        @include('website.partials.section-heading', [
            'eyebrow'   => 'Berita',
            'title'     => 'Berita <span class="text-primary">Terbaru</span>',
            'subtitle'  => 'Informasi dan pembaruan terkini dari kami.',
        ])

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $item)
                @include('website.partials.news-card', ['item' => $item])
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl hover:bg-slate-700 dark:hover:bg-slate-100 transition-all duration-200 hover:scale-[1.02] shadow-md">
                Lihat Semua Berita
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif


{{-- ======================== CTA PENUTUP ======================== --}}
<section class="py-16 bg-white dark:bg-dark">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mb-4">
            Hubungi Kami
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mb-8">
            Punya pertanyaan atau ingin bekerja sama? Kami siap membantu.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('about') }}#contact"
               class="inline-flex items-center gap-2 px-7 py-3.5 bg-accent hover:bg-accent-dark text-white font-bold rounded-xl shadow-lg shadow-orange-500/25 transition-all duration-200 hover:scale-[1.03]">
                Hubungi Kami
            </a>
            @guest
            <a href="{{ route('auth.login') }}"
               class="inline-flex items-center gap-2 px-7 py-3.5 border-2 border-slate-200 dark:border-dark-border text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary font-semibold rounded-xl transition-all duration-200">
                Masuk
            </a>
            @endguest
        </div>
    </div>
</section>

@endsection
