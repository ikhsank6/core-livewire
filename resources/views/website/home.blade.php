@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' - Beranda')

@section('content')

{{-- ================================================================
     SECTION 1: HERO
     ================================================================ --}}
@if($carousels->count() > 0)
{{-- ── Hero with Carousel: Full-screen immersive carousel ── --}}
<section class="relative overflow-hidden"
    x-data="{
        current: 0,
        total: {{ $carousels->count() }},
        timer: null,
        start() { this.timer = setInterval(() => this.next(), 5000); },
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
         class="hero-slide min-h-[620px] md:min-h-[700px] flex items-center"
         style="background-image: url('{{ $carousel->image ? Storage::url($carousel->image) : '' }}');">

        {{-- Deep gradient overlay --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(105deg, rgba(13,17,23,0.95) 0%, rgba(13,17,23,0.75) 50%, rgba(13,17,23,0.35) 100%);"></div>

        {{-- Decorative orbs --}}
        <div class="absolute top-1/4 right-1/4 w-72 h-72 bg-primary rounded-full filter blur-[100px] opacity-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent rounded-full filter blur-[120px] opacity-6 pointer-events-none"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-28">
            <div class="max-w-2xl">
                {{-- Badge --}}
                @if($carousel->description)
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full text-white/90 text-sm font-semibold mb-6 font-heading">
                    <span class="w-1.5 h-1.5 bg-accent rounded-full animate-float-slow"></span>
                    {{ $carousel->description }}
                </div>
                @endif

                <h1 class="font-heading font-extrabold text-white leading-tight mb-6 text-4xl sm:text-5xl lg:text-6xl">
                    {{ $carousel->title }}
                </h1>

                @if($carousel->button_text)
                <div class="flex flex-wrap gap-3 mt-8">
                    <a href="{{ $carousel->button_link ?? route('about') }}" class="btn-primary-solid">
                        {{ $carousel->button_text }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('about') }}" class="btn-outline-white">
                        Tentang Kami
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    {{-- Carousel controls --}}
    @if($carousels->count() > 1)
    <button @click="prev()" aria-label="Slide sebelumnya"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white flex items-center justify-center transition-all hover:scale-110 focus-visible:ring-2 focus-visible:ring-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click="next()" aria-label="Slide berikutnya"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20 text-white flex items-center justify-center transition-all hover:scale-110 focus-visible:ring-2 focus-visible:ring-primary">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- Dot indicators --}}
    <div class="absolute bottom-7 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
        @foreach($carousels as $i => $c)
        <button @click="go({{ $i }})"
                :class="current === {{ $i }} ? 'w-7 bg-primary' : 'w-2 bg-white/40 hover:bg-white/70'"
                class="h-2 rounded-full transition-all duration-300"
                :aria-selected="current === {{ $i }}"
                aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>

    {{-- Slide counter --}}
    <div class="absolute bottom-7 right-6 z-20 text-white/50 text-xs font-heading font-semibold hidden md:block">
        <span x-text="current + 1"></span> / {{ $carousels->count() }}
    </div>
    @endif

    {{-- Scroll hint --}}
    <div class="absolute bottom-7 right-6 z-20 hidden md:flex flex-col items-center gap-1.5 cursor-pointer opacity-50 hover:opacity-90 transition-opacity"
         @click="window.scrollBy({top: window.innerHeight, behavior:'smooth'})"
         aria-hidden="true">
        <span class="text-[10px] font-bold tracking-[.25em] uppercase text-white">Scroll</span>
        <div class="w-5 h-8 rounded-full border border-white/30 flex justify-center pt-1.5">
            <div class="w-1 h-2 bg-white/80 rounded-full animate-scroll-dot"></div>
        </div>
    </div>
</section>

@else
{{-- ── Hero Fallback: 2-column Aveit-style ── --}}
<section class="relative overflow-hidden min-h-[680px] flex items-center"
         style="background: linear-gradient(135deg, #0d1117 0%, #161b25 55%, #0a1628 100%);">

    {{-- Decorative blobs --}}
    <div class="shape-blob w-[500px] h-[500px] bg-primary" style="top: -80px; right: -80px; opacity: 0.1;"></div>
    <div class="shape-blob w-72 h-72 bg-accent" style="bottom: 0; left: -60px; opacity: 0.07;"></div>
    {{-- Grid dot pattern --}}
    <div class="absolute inset-0 opacity-[0.04] pointer-events-none"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 w-full">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left: Text content --}}
            <div class="aos-init aos-left">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/8 backdrop-blur-sm border border-white/12 rounded-full text-white/80 text-sm font-semibold mb-7 font-heading">
                    <span class="w-2 h-2 bg-accent rounded-full animate-float-slow"></span>
                    Platform CMS Modern
                </div>

                <h1 class="font-heading font-extrabold text-white text-4xl sm:text-5xl lg:text-6xl leading-tight mb-6">
                    {{ $aboutUs->company_name ?? config('app.name') }}
                </h1>

                @if($aboutUs?->description)
                <p class="text-white/60 text-lg leading-relaxed mb-8 max-w-xl">
                    {{ Str::limit(strip_tags($aboutUs->description), 200) }}
                </p>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('about') }}" class="btn-primary-solid">
                        Tentang Kami
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('news.index') }}" class="btn-outline-white">
                        Lihat Berita
                    </a>
                </div>

                {{-- Trust indicators --}}
                <div class="flex items-center gap-6 mt-8 pt-8 border-t border-white/10">
                    @if($news->count() > 0)
                    <div>
                        <p class="text-white font-heading font-bold text-xl">{{ $news->count() }}+</p>
                        <p class="text-white/50 text-xs">Artikel Berita</p>
                    </div>
                    <div class="w-px h-8 bg-white/15"></div>
                    @endif
                    <div>
                        <p class="text-white font-heading font-bold text-xl">Aman</p>
                        <p class="text-white/50 text-xs">RBAC & Multi-Peran</p>
                    </div>
                    <div class="w-px h-8 bg-white/15"></div>
                    <div>
                        <p class="text-white font-heading font-bold text-xl">Cepat</p>
                        <p class="text-white/50 text-xs">Livewire SPA</p>
                    </div>
                </div>
            </div>

            {{-- Right: Illustration --}}
            <div class="hidden lg:flex justify-center items-center aos-init aos-right">
                {{-- Abstract dashboard mockup (SVG) --}}
                <div class="relative">
                    {{-- Browser frame --}}
                    <div class="w-[480px] bg-dark-card rounded-2xl border border-white/10 shadow-2xl shadow-black/50 overflow-hidden animate-float"
                         style="box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);">
                        {{-- Browser bar --}}
                        <div class="flex items-center gap-2 px-4 py-3 bg-dark-border border-b border-white/5">
                            <div class="w-3 h-3 rounded-full bg-red-500/60"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500/60"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500/60"></div>
                            <div class="flex-1 mx-4 h-6 rounded-md bg-white/5 flex items-center px-3">
                                <div class="w-2 h-2 rounded-full bg-green-500/50 mr-2"></div>
                                <div class="h-2 w-32 bg-white/10 rounded-sm"></div>
                            </div>
                        </div>
                        {{-- Dashboard preview --}}
                        <div class="p-4 bg-dark space-y-3">
                            {{-- Stat cards row --}}
                            <div class="grid grid-cols-3 gap-2">
                                @foreach([['bg-primary/20 text-primary-light','38','Berita'],['bg-green-500/20 text-green-400','12','Pengguna'],['bg-accent/20 text-accent','5','Kategori']] as $stat)
                                <div class="bg-dark-card rounded-lg p-3 border border-white/5">
                                    <p class="text-xs {{ $stat[0] }} font-bold font-heading">{{ $stat[2] }}</p>
                                    <p class="text-white text-xl font-heading font-bold mt-1">{{ $stat[1] }}</p>
                                </div>
                                @endforeach
                            </div>
                            {{-- Chart placeholder --}}
                            <div class="bg-dark-card rounded-lg p-3 border border-white/5 h-24 flex items-end gap-1 px-4">
                                @foreach([35,55,40,70,50,80,65,90] as $h)
                                <div class="flex-1 rounded-t-sm bg-primary/40 transition-all" style="height: {{ $h }}%;"></div>
                                @endforeach
                            </div>
                            {{-- List rows --}}
                            @for($i=0;$i<3;$i++)
                            <div class="flex items-center gap-2 bg-dark-card rounded-lg p-2.5 border border-white/5">
                                <div class="w-7 h-7 rounded-md bg-primary/20 shrink-0"></div>
                                <div class="flex-1 space-y-1">
                                    <div class="h-2 bg-white/10 rounded-sm w-3/4"></div>
                                    <div class="h-1.5 bg-white/5 rounded-sm w-1/2"></div>
                                </div>
                                <div class="h-5 w-12 rounded-full bg-green-500/20"></div>
                            </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Floating badge --}}
                    <div class="absolute -top-4 -right-6 bg-white rounded-xl shadow-xl px-4 py-2.5 flex items-center gap-2.5 animate-float-slow">
                        <div class="w-8 h-8 rounded-lg bg-primary/15 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-900 font-heading font-bold text-xs">Keamanan RBAC</p>
                            <p class="text-slate-400 text-[11px]">Multi-peran aktif</p>
                        </div>
                    </div>

                    {{-- Floating notification --}}
                    <div class="absolute -bottom-4 -left-6 bg-white rounded-xl shadow-xl px-4 py-2.5 flex items-center gap-2.5 animate-float" style="animation-delay: 1.5s;">
                        <div class="w-8 h-8 rounded-lg bg-green-500/15 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-slate-900 font-heading font-bold text-xs">Livewire Real-time</p>
                            <p class="text-slate-400 text-[11px]">Tanpa page reload</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


{{-- ================================================================
     SECTION 2: FEATURES STRIP (Dark — terinspirasi Aveit)
     ================================================================ --}}
<section class="bg-section-dark py-20 relative overflow-hidden">
    {{-- Subtle grid --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>
    {{-- Orb --}}
    <div class="shape-blob w-96 h-96 bg-primary" style="top: -100px; left: 50%; transform:translateX(-50%); opacity: 0.07;"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('website.partials.section-heading', [
            'eyebrow'  => 'Mengapa Memilih Kami',
            'title'    => 'Platform CMS yang <span style="color: #6b92f7;">Lengkap & Handal</span>',
            'subtitle' => 'Dibangun dengan teknologi terkini untuk kemudahan pengelolaan konten dan keamanan data organisasi Anda.',
            'light'    => true,
        ])

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Feature 1 --}}
            <div class="feature-card-dark aos-init aos-delay-1">
                <div class="feature-icon-wrap">
                    <svg class="w-6 h-6 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h4 class="font-heading font-bold text-white text-lg mb-2">Akses Multi-Peran</h4>
                <p class="text-dark-text text-sm leading-relaxed">Kelola hak akses setiap pengguna dengan sistem RBAC yang fleksibel. Tetapkan peran berbeda untuk admin, editor, dan kontributor.</p>
            </div>

            {{-- Feature 2 --}}
            <div class="feature-card-dark aos-init aos-delay-2">
                <div class="feature-icon-wrap">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h4 class="font-heading font-bold text-white text-lg mb-2">Manajemen Konten</h4>
                <p class="text-dark-text text-sm leading-relaxed">Buat, edit, dan publikasikan berita dengan mudah. Dukung kategori, gambar, dan slug otomatis untuk SEO yang optimal.</p>
            </div>

            {{-- Feature 3 --}}
            <div class="feature-card-dark aos-init aos-delay-3">
                <div class="feature-icon-wrap">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h4 class="font-heading font-bold text-white text-lg mb-2">Notifikasi Real-time</h4>
                <p class="text-dark-text text-sm leading-relaxed">Sistem notifikasi berbasis peran yang menginformasikan tim Anda secara real-time tanpa perlu reload halaman.</p>
            </div>

            {{-- Feature 4 --}}
            <div class="feature-card-dark aos-init aos-delay-1">
                <div class="feature-icon-wrap">
                    <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                </div>
                <h4 class="font-heading font-bold text-white text-lg mb-2">Dashboard Analitik</h4>
                <p class="text-dark-text text-sm leading-relaxed">Pantau statistik pengguna dan distribusi peran melalui visualisasi grafik interaktif langsung dari dashboard admin.</p>
            </div>

            {{-- Feature 5 --}}
            <div class="feature-card-dark aos-init aos-delay-2">
                <div class="feature-icon-wrap">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="font-heading font-bold text-white text-lg mb-2">Media Management</h4>
                <p class="text-dark-text text-sm leading-relaxed">Upload dan kelola gambar untuk berita, carousel, dan profil. Didukung sistem Media terpusat dengan UUID unik.</p>
            </div>

            {{-- Feature 6 --}}
            <div class="feature-card-dark aos-init aos-delay-3">
                <div class="feature-icon-wrap">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h4 class="font-heading font-bold text-white text-lg mb-2">Keamanan Berlapis</h4>
                <p class="text-dark-text text-sm leading-relaxed">Security headers, CSRF protection, rate limiting, dan enkripsi password bcrypt 12 rounds menjaga data Anda tetap aman.</p>
            </div>
        </div>
    </div>
</section>


{{-- ================================================================
     SECTION 3: TENTANG KAMI (2-kolom Aveit "Clients" style)
     ================================================================ --}}
@if($aboutUs)
<section class="py-24 bg-white dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Visual --}}
            <div class="flex justify-center lg:justify-start aos-init aos-left">
                @if($aboutUs->logo)
                <div class="relative">
                    <div class="bg-section-gray dark:bg-dark-card border border-slate-100 dark:border-dark-border rounded-2xl p-12 flex items-center justify-center max-w-sm w-full shadow-lg">
                        <img src="{{ Storage::url($aboutUs->logo) }}"
                             alt="{{ $aboutUs->company_name }}"
                             class="max-h-48 max-w-full object-contain dark:brightness-0 dark:invert">
                    </div>
                    {{-- Decorative blob --}}
                    <div class="absolute -bottom-8 -right-8 w-40 h-40 bg-primary rounded-2xl opacity-8 -z-10"></div>
                    <div class="absolute -top-4 -left-4 w-24 h-24 bg-accent rounded-xl opacity-6 -z-10"></div>
                </div>
                @else
                <div class="relative w-80 h-80 flex items-center justify-center">
                    <div class="w-full h-full bg-gradient-to-br from-primary/10 to-accent/8 rounded-3xl border border-primary/10 flex items-center justify-center shadow-xl">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary/30">
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/>
                                </svg>
                            </div>
                            <p class="font-heading font-bold text-slate-700 dark:text-white">{{ $aboutUs->company_name ?? config('app.name') }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right: Text --}}
            <div class="aos-init aos-right">
                @include('website.partials.section-heading', [
                    'eyebrow' => 'Tentang Kami',
                    'title'   => 'Kenali <span style="color: #3a6cf4;">Lebih Dekat</span> Tentang Kami',
                    'align'   => 'left',
                ])

                @if($aboutUs->description)
                <div class="prose dark:prose-invert mb-8">
                    {!! Str::limit(strip_tags($aboutUs->description), 400) !!}
                </div>
                @endif

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('about') }}" class="btn-primary-solid">
                        Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('about') }}#contact" class="btn-outline-primary">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


{{-- ================================================================
     SECTION 4: BERITA TERBARU
     ================================================================ --}}
@if($news->count() > 0)
<section class="py-24 bg-section-gray dark:bg-dark-card">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @include('website.partials.section-heading', [
            'eyebrow'  => 'Blog & Berita',
            'title'    => 'Berita <span style="color: #3a6cf4;">Terkini</span>',
            'subtitle' => 'Ikuti informasi dan perkembangan terbaru dari kami.',
        ])

        {{-- News grid: featured + cards --}}
        @if($news->count() === 1)
        <div class="max-w-2xl mx-auto">
            @include('website.partials.news-card', ['item' => $news->first()])
        </div>
        @elseif($news->count() === 2)
        <div class="grid sm:grid-cols-2 gap-6">
            @foreach($news as $item)
                <div class="aos-init aos-init aos-delay-{{ $loop->index + 1 }}">
                    @include('website.partials.news-card', ['item' => $item])
                </div>
            @endforeach
        </div>
        @else
        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Featured: first item spans full or 2 cols --}}
            <div class="lg:col-span-2 aos-init">
                @include('website.partials.news-card', ['item' => $news->first(), 'featured' => true])
            </div>
            {{-- Side cards --}}
            <div class="flex flex-col gap-6">
                @foreach($news->skip(1)->take(2) as $item)
                <div class="flex-1 aos-init aos-delay-{{ $loop->index + 1 }}">
                    @include('website.partials.news-card', ['item' => $item])
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- CTA to all news --}}
        <div class="text-center mt-12">
            <a href="{{ route('news.index') }}" class="btn-outline-primary inline-flex">
                Lihat Semua Berita
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif


{{-- ================================================================
     SECTION 5: CTA PENUTUP (Dark navy)
     ================================================================ --}}
<section class="py-24 bg-section-dark relative overflow-hidden">
    {{-- Orbs --}}
    <div class="shape-blob w-96 h-96 bg-primary" style="top: -80px; right: -60px; opacity: 0.1;"></div>
    <div class="shape-blob w-72 h-72 bg-accent" style="bottom: -60px; left: -40px; opacity: 0.07;"></div>
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <div class="eyebrow-pill-light inline-flex items-center gap-2 mb-6">
            <span class="eyebrow-dot"></span>
            Mari Terhubung
        </div>
        <h2 class="font-heading font-extrabold text-white text-3xl sm:text-4xl lg:text-5xl leading-tight mb-5">
            Punya Pertanyaan atau Ingin <span style="color: #6b92f7;">Bekerja Sama?</span>
        </h2>
        <p class="text-white/55 text-lg mb-10 leading-relaxed">
            Tim kami siap membantu Anda. Hubungi kami sekarang atau daftar untuk mulai menggunakan platform kami.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('about') }}#contact" class="btn-primary-solid">
                Hubungi Kami
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </a>
            @guest
            <a href="{{ route('auth.register') }}" class="btn-outline-white">
                Daftar Sekarang
            </a>
            @endguest
        </div>
    </div>
</section>



@endsection
