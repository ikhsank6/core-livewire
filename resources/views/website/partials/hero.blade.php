@php
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');

    // Quick links, the pattern the national certification body uses for its main tasks.
    $quick = [
        ['title' => 'Skema sertifikasi',    'text' => 'Pilih okupasi yang ingin diuji.',     'href' => '#skema',  'icon' => 'heroicon-o-document-check'],
        ['title' => 'Alur dan persyaratan', 'text' => 'Dari akun sampai sertifikat terbit.', 'href' => '#alur',   'icon' => 'heroicon-o-queue-list'],
        ['title' => 'Jadwal uji',           'text' => 'Lihat kapan dan di mana ujinya.',     'href' => '#jadwal', 'icon' => 'heroicon-o-calendar-days'],
    ];

    // Field tags (pulled from the skema section so they stay in sync)
    $fieldTags = ['Teknologi informasi', 'Administrasi', 'Keuangan', 'Desain kreatif', 'Pemasaran'];

    // The specimen: placeholders only, never a real person or a real certificate number.
    $units = [
        'Unit kompetensi 1',
        'Unit kompetensi 2',
        'Unit kompetensi 3',
    ];
@endphp

<section class="hero on-dark relative isolate flex flex-col overflow-hidden bg-navy text-white lg:min-h-[calc(100svh-3.3125rem)]"
         x-data="{
            rot: 0,
            reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            tilt(e) {
                if (this.reduce) return;
                const r = this.$el.getBoundingClientRect();
                this.rot = ((e.clientX - r.left) / r.width - 0.5) * 16;
            }
         }"
         @pointermove="tilt($event)">

    {{-- Background rosettes --}}
    <div class="rosette -right-48 -top-40 -z-10 w-[52rem] max-w-none text-white/[0.07]" :style="`--rot: ${rot}deg`" aria-hidden="true"></div>
    <div class="rosette -left-72 -bottom-80 -z-10 w-[40rem] max-w-none text-white/[0.045]" :style="`--rot: ${-rot}deg`" aria-hidden="true"></div>

    <div class="wrap hero-grid grid w-full flex-1 content-center items-center gap-8 py-8 md:py-10 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] lg:gap-12">

        {{-- ── LEFT COLUMN: headline + search + tags ── --}}
        <div>
            <h1 class="display text-white max-w-[20ch]" style="font-size: clamp(1.9rem, min(3.6vw, 6.4vh), 3.1rem);">
                Buktikan kompetensi Anda dengan sertifikat profesi
            </h1>

            <p class="mt-4 text-base leading-relaxed text-white/70 max-w-[42ch]">
                Lihat skema sertifikasi yang tersedia dan pilih yang sesuai bidang kerja Anda.
            </p>

            {{-- Search bar --}}
            <form action="#skema" method="get" class="mt-6 flex items-center gap-2 max-w-lg">
                <label for="hero-search" class="sr-only">Cari skema atau bidang kerja</label>
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-white/40" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input id="hero-search" type="search" name="q" placeholder="Nama skema atau bidang kerja"
                           class="w-full h-11 pl-10 pr-4 rounded-lg bg-white/10 border border-white/20 text-white text-sm placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-gold/60 focus:border-gold/60 transition-colors">
                </div>
                <button type="submit"
                        class="btn btn-accent h-11 px-5 text-sm shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
            </form>

            {{-- Field tags --}}
            <div class="hero-tags mt-3 flex flex-wrap gap-2">
                @foreach($fieldTags as $tag)
                    <a href="#skema"
                       class="inline-flex items-center gap-1.5 h-8 px-3 rounded-md bg-white/10 border border-white/15 text-xs font-semibold text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        {{ $tag }}
                    </a>
                @endforeach
            </div>

        </div>

        {{-- ── RIGHT COLUMN: certificate illustration with animation ── --}}
        <figure class="relative mx-auto w-full max-w-sm lg:max-w-[24rem]"
                aria-label="Ilustrasi sertifikat kompetensi">

            {{-- Shadow stack card --}}
            <div class="absolute inset-0 translate-x-5 translate-y-5 rotate-[4deg] rounded-2xl bg-white/10" aria-hidden="true"></div>
            <div class="absolute inset-0 translate-x-2.5 translate-y-2.5 rotate-[2deg] rounded-2xl bg-white/15" aria-hidden="true"></div>

            {{-- Main certificate card --}}
            <div class="relative rounded-2xl bg-white shadow-[0_40px_80px_-20px_rgba(0,0,0,0.6)] overflow-hidden">

                {{-- Guilloche watermark --}}
                <div class="rosette absolute -bottom-16 -right-16 w-56 text-navy/[0.07]" aria-hidden="true"></div>

                {{-- Certificate inner content --}}
                <div class="relative p-5 sm:p-6 text-[#14213A]">

                    {{-- Header ornament --}}
                    <div class="flex justify-center mb-2" aria-hidden="true">
                        {{-- Small decorative diamond chain --}}
                        <svg width="80" height="10" viewBox="0 0 80 10" fill="none" class="text-gold opacity-80">
                            <path d="M0 5h10M70 5h10" stroke="currentColor" stroke-width="1.5"/>
                            <circle cx="20" cy="5" r="3" fill="currentColor" opacity="0.5"/>
                            <circle cx="30" cy="5" r="2" fill="currentColor" opacity="0.7"/>
                            <circle cx="40" cy="5" r="3.5" fill="currentColor"/>
                            <circle cx="50" cy="5" r="2" fill="currentColor" opacity="0.7"/>
                            <circle cx="60" cy="5" r="3" fill="currentColor" opacity="0.5"/>
                        </svg>
                    </div>

                    {{-- Outer border line --}}
                    <div class="absolute inset-3 rounded-xl pointer-events-none" style="border: 1.5px solid #E7BA4B33;" aria-hidden="true"></div>

                    {{-- Title --}}
                    <div class="text-center mt-1 mb-3">
                        <h2 class="text-[1.1rem] font-extrabold tracking-tight text-navy leading-tight">Sertifikat Kompetensi</h2>
                    </div>

                    {{-- Recipient --}}
                    <p class="text-center text-xs text-gray-400 font-medium mb-1">Diberikan kepada</p>
                    <p class="text-center text-lg font-bold italic text-navy/80 mb-1" style="font-family: Georgia, serif;">Nama peserta</p>
                    <div class="mx-auto w-24 h-px bg-gold/40 my-2"></div>

                    {{-- Scheme --}}
                    <p class="text-center text-xs text-gray-400 font-medium mb-1">atas skema</p>
                    <p class="text-center text-sm font-extrabold text-navy tracking-tight mb-3">Nama skema sertifikasi</p>

                    {{-- Units --}}
                    <ul class="space-y-2 mb-3" aria-label="Unit kompetensi (contoh)">
                        @foreach($units as $unit)
                            <li class="flex items-center justify-between gap-3 text-xs">
                                <span class="flex items-center gap-2 text-navy/80">
                                    {{-- Animated checkmark circle --}}
                                    <span class="cert-check flex h-4.5 w-4.5 shrink-0 items-center justify-center rounded-full border-2 border-navy/40 bg-navy/5" aria-hidden="true">
                                        <svg class="h-2.5 w-2.5 text-navy" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 6l3 3 5-5"/>
                                        </svg>
                                    </span>
                                    {{ $unit }}
                                </span>
                                <span class="font-semibold text-navy/60">Kompeten</span>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Kompeten seal (animated stamp) --}}
                    <div class="flex justify-end mb-1">
                        <div class="cert-seal relative flex items-center justify-center w-16 h-16" aria-hidden="true">
                            {{-- Outer ring --}}
                            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 80 80" fill="none">
                                <circle cx="40" cy="40" r="36" stroke="#E7BA4B" stroke-width="2.5" stroke-dasharray="6 3"/>
                                <circle cx="40" cy="40" r="30" stroke="#E7BA4B" stroke-width="1" stroke-opacity="0.5"/>
                            </svg>
                            {{-- Inner fill --}}
                            <div class="absolute inset-2.5 rounded-full flex flex-col items-center justify-center"
                                 style="background: radial-gradient(circle, #F0CB6B, #E7BA4B);">
                                <svg class="w-6 h-6 text-navy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                                <span class="text-[6px] font-extrabold text-navy tracking-wider -mt-0.5">KOMPETEN</span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer note --}}
                    <p class="text-center text-[9px] text-gray-400 mt-1">Ilustrasi sertifikat. Bukan dokumen resmi.</p>
                </div>
            </div>
        </figure>
    </div>

    {{-- Quick links: three tiles that jump to the sections below --}}
    <div class="relative pb-6 sm:pb-8">
        <ul class="wrap grid gap-3.5 sm:grid-cols-3">
            @foreach($quick as $link)
                <li>
                    <a href="{{ $link['href'] }}"
                       class="group flex items-center gap-3.5 rounded-xl border border-white/15 bg-white/[0.06] p-3.5 backdrop-blur-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-gold/50 hover:bg-white/10 hover:shadow-lg hover:shadow-black/20">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold/15 text-gold border border-gold/30 transition-colors duration-200 group-hover:bg-gold group-hover:text-navy group-hover:border-gold">
                            @svg($link['icon'], 'h-5 w-5')
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-bold text-white tracking-tight group-hover:text-gold-soft transition-colors">{{ $link['title'] }}</span>
                            <span class="mt-0.5 block text-xs text-white/70 line-clamp-1 leading-snug">{{ $link['text'] }}</span>
                        </span>
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-white/35 transition-all duration-200 group-hover:bg-white/10 group-hover:text-gold-soft group-hover:translate-y-0.5" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

<style>
/* The assessment decision, on a loop. One 7s cycle: the three unit ticks are drawn one after
   another, the KOMPETEN seal lands, the result is held, then it fades and the cycle starts again.
   Only the ticks and the seal move; the certificate itself stays still. */
.cert-seal {
    transform-origin: center;
    animation: seal-loop 7s ease-out infinite;
}
@keyframes seal-loop {
    0%, 27%  { opacity: 0; transform: scale(2.2) rotate(-25deg); }
    35%      { opacity: 1; transform: scale(0.9) rotate(5deg); }
    40%      { transform: scale(1.05) rotate(-3deg); }
    45%, 89% { opacity: 1; transform: scale(1) rotate(0deg); }
    96%,100% { opacity: 0; transform: scale(0.92) rotate(0deg); }
}

.cert-check svg {
    stroke-dasharray: 20;
    stroke-dashoffset: 20;
    animation-duration: 7s;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
}
li:nth-child(1) .cert-check svg { animation-name: check-loop-1; }
li:nth-child(2) .cert-check svg { animation-name: check-loop-2; }
li:nth-child(3) .cert-check svg { animation-name: check-loop-3; }

@keyframes check-loop-1 {
    0%, 4%   { stroke-dashoffset: 20; }
    11%, 89% { stroke-dashoffset: 0; }
    96%,100% { stroke-dashoffset: 20; }
}
@keyframes check-loop-2 {
    0%, 11%  { stroke-dashoffset: 20; }
    18%, 89% { stroke-dashoffset: 0; }
    96%,100% { stroke-dashoffset: 20; }
}
@keyframes check-loop-3 {
    0%, 18%  { stroke-dashoffset: 20; }
    25%, 89% { stroke-dashoffset: 0; }
    96%,100% { stroke-dashoffset: 20; }
}

/* Short screens: keep the whole first screen visible, nothing cut at the bottom edge. */
@media (min-width: 1024px) and (max-height: 760px) {
    .hero .hero-tags { display: none; }
    .hero .hero-grid { padding-top: 1.25rem; padding-bottom: 1.25rem; }
}
@media (min-width: 1024px) and (max-height: 620px) {
    .hero figure { max-width: 21rem; }
    .hero figure p.text-\[9px\] { display: none; }
}

/* Reduce motion override */
@media (prefers-reduced-motion: reduce) {
    .cert-seal { animation: none !important; }
    .cert-check svg { animation: none !important; stroke-dashoffset: 0; }
}
</style>
