@php
    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');

    // Quick links, the pattern the national certification body uses for its main tasks.
    $quick = [
        ['title' => 'Skema sertifikasi',    'text' => 'Pilih okupasi yang ingin diuji.',     'href' => route('skema.index'),  'icon' => 'heroicon-o-document-check'],
        ['title' => 'Alur dan persyaratan', 'text' => 'Dari akun sampai sertifikat terbit.', 'href' => '#alur',   'icon' => 'heroicon-o-queue-list'],
        ['title' => 'Jadwal uji',           'text' => 'Lihat kapan dan di mana ujinya.',     'href' => route('jadwal.index'), 'icon' => 'heroicon-o-calendar-days'],
    ];

    // Field tags (pulled from the skema section so they stay in sync)
    $fieldTags = collect(config('sample-schemes.schemes'))->pluck('field')->unique()->values()->all();

    // The specimen: placeholders only, never a real person or a real certificate number.
    $units = [
        'Unit kompetensi 1',
        'Unit kompetensi 2',
        'Unit kompetensi 3',
    ];
@endphp

<section class="hero on-dark relative isolate flex flex-col overflow-hidden bg-navy text-white lg:min-h-[min(calc(100svh-3.3125rem),64rem)]"
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
            <form action="{{ route('skema.index') }}" method="get" class="mt-6 flex items-center gap-2 max-w-lg">
                <label for="hero-search" class="sr-only">Cari skema atau bidang kerja</label>
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-white/40" aria-hidden="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input id="hero-search" type="search" name="q" placeholder="Cari skema atau bidang"
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
                    <a href="{{ route('skema.index') }}?bidang={{ urlencode($tag) }}"
                       class="inline-flex items-center gap-1.5 h-8 px-3 rounded-md bg-white/10 border border-white/15 text-xs font-semibold text-white/80 hover:bg-white/20 hover:text-white transition-colors">
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        {{ $tag }}
                    </a>
                @endforeach
            </div>

        </div>

        {{-- ── RIGHT COLUMN: a sample certificate on landscape paper ──
             Shape only is borrowed from national competency certificates (landscape sheet, ornamental
             lattice border, corner flourishes, central watermark, seal and signature). It carries no
             real name, number, emblem or institution, and is labelled as an illustration. --}}
        <figure class="cert-figure relative mx-auto w-full max-w-md lg:max-w-[30rem]"
                aria-label="Ilustrasi sertifikat kompetensi">

            {{-- Sheets underneath --}}
            <div class="absolute inset-0 translate-x-4 translate-y-4 rotate-[3deg] rounded-md bg-[#E8D3A8]/15" aria-hidden="true"></div>
            <div class="absolute inset-0 translate-x-2 translate-y-2 rotate-[1.5deg] rounded-md bg-[#E8D3A8]/25" aria-hidden="true"></div>

            {{-- The paper --}}
            <div class="cert-paper relative overflow-hidden rounded-md shadow-[0_40px_80px_-20px_rgba(0,0,0,0.6)]">

                {{-- Lattice border band --}}
                <div class="cert-band absolute inset-2" aria-hidden="true"></div>

                {{-- Inner sheet --}}
                <div class="cert-sheet absolute inset-[13px]" aria-hidden="true">
                    <div class="rosette absolute left-1/2 top-1/2 w-72 -translate-x-1/2 -translate-y-1/2 text-[#7A4B24]/[0.09]"></div>
                    {{-- Corner flourishes --}}
                    <svg class="absolute left-1 top-1 h-7 w-7 text-[#7A4B24]" viewBox="0 0 28 28" fill="none"><path d="M2 26V11a9 9 0 0 1 9-9h15M2 17V12a10 10 0 0 1 10-10h5" stroke="currentColor" stroke-width="1.2"/><circle cx="6" cy="6" r="1.6" fill="currentColor"/></svg>
                    <svg class="absolute right-1 top-1 h-7 w-7 rotate-90 text-[#7A4B24]" viewBox="0 0 28 28" fill="none"><path d="M2 26V11a9 9 0 0 1 9-9h15M2 17V12a10 10 0 0 1 10-10h5" stroke="currentColor" stroke-width="1.2"/><circle cx="6" cy="6" r="1.6" fill="currentColor"/></svg>
                    <svg class="absolute bottom-1 right-1 h-7 w-7 rotate-180 text-[#7A4B24]" viewBox="0 0 28 28" fill="none"><path d="M2 26V11a9 9 0 0 1 9-9h15M2 17V12a10 10 0 0 1 10-10h5" stroke="currentColor" stroke-width="1.2"/><circle cx="6" cy="6" r="1.6" fill="currentColor"/></svg>
                    <svg class="absolute bottom-1 left-1 h-7 w-7 -rotate-90 text-[#7A4B24]" viewBox="0 0 28 28" fill="none"><path d="M2 26V11a9 9 0 0 1 9-9h15M2 17V12a10 10 0 0 1 10-10h5" stroke="currentColor" stroke-width="1.2"/><circle cx="6" cy="6" r="1.6" fill="currentColor"/></svg>
                </div>

                {{-- Content --}}
                <div class="relative px-9 py-7 text-center text-[#3B2412]" style="font-family: Georgia, 'Times New Roman', serif;">

                    <p class="text-[9px] font-bold uppercase tracking-[0.22em] text-[#6B4A2E]">Lembaga Sertifikasi Profesi</p>
                    <h2 class="mt-1 text-[1.15rem] font-bold uppercase leading-tight tracking-[0.14em] text-[#3B2412]">Sertifikat Kompetensi</h2>
                    <p class="mt-0.5 text-[9px] tracking-wide text-[#75573A]">Nomor 000/0000 (contoh)</p>

                    <p class="mt-3 text-[10px] italic text-[#75573A]">Diberikan kepada</p>
                    <p class="text-[1.35rem] font-bold italic leading-tight text-[#3B2412]">Nama peserta</p>
                    <div class="mx-auto my-1.5 h-px w-40" style="background: linear-gradient(to right, transparent, #7A4B24, transparent);"></div>
                    <p class="text-[10px] italic text-[#75573A]">atas skema</p>
                    <p class="text-[0.8125rem] font-bold uppercase tracking-wider text-[#3B2412]">Nama skema sertifikasi</p>

                    <div class="mt-3 grid grid-cols-[1fr_auto] items-end gap-4 text-left">
                        <ul class="space-y-1.5" aria-label="Unit kompetensi (contoh)">
                            @foreach($units as $unit)
                                <li class="flex items-center gap-2 text-[11px] text-[#3B2412]">
                                    <span class="cert-check flex h-4 w-4 shrink-0 items-center justify-center rounded-full border border-[#7A4B24]/60 bg-[#7A4B24]/10" aria-hidden="true">
                                        <svg class="h-2.5 w-2.5 text-[#5A3418]" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 6l3 3 5-5"/>
                                        </svg>
                                    </span>
                                    {{ $unit }}
                                </li>
                            @endforeach
                        </ul>

                        {{-- Seal and signature --}}
                        <div class="flex flex-col items-center">
                            <div class="cert-seal relative flex h-16 w-16 items-center justify-center" aria-hidden="true">
                                <svg class="absolute inset-0 h-full w-full" viewBox="0 0 80 80" fill="none">
                                    <circle cx="40" cy="40" r="36" stroke="#8A5A2B" stroke-width="2.5" stroke-dasharray="6 3"/>
                                    <circle cx="40" cy="40" r="30" stroke="#8A5A2B" stroke-width="1" stroke-opacity="0.55"/>
                                </svg>
                                <div class="absolute inset-2.5 flex flex-col items-center justify-center rounded-full"
                                     style="background: radial-gradient(circle, #F0CB6B, #D2A13F);">
                                    <svg class="h-5 w-5 text-[#3B2412]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6L9 17l-5-5"/>
                                    </svg>
                                    <span class="-mt-0.5 text-[6px] font-extrabold tracking-wider text-[#3B2412]" style="font-family: Inter, sans-serif;">KOMPETEN</span>
                                </div>
                            </div>
                            <div class="mt-1 w-20 border-t border-[#7A4B24]/60 pt-0.5 text-center text-[8px] italic text-[#75573A]">Ketua LSP</div>
                        </div>
                    </div>

                    <p class="cert-note mt-2.5 text-[8px] tracking-wide text-[#75573A]" style="font-family: Inter, sans-serif;">Ilustrasi sertifikat. Bukan dokumen resmi.</p>
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

/* Sample certificate paper: warm parchment, brown ink, a lattice border band and a darker rim. */
.cert-paper {
    background: radial-gradient(120% 90% at 50% 40%, #FBF3E2 0%, #F3E5C6 62%, #E6D1A4 100%);
    border: 1px solid #6B4423;
}
.cert-band {
    border: 1px solid #7A4B24;
    background-color: #E9D5AB;
    background-image:
        repeating-linear-gradient(45deg, rgba(122, 75, 36, 0.55) 0 1px, transparent 1px 5px),
        repeating-linear-gradient(-45deg, rgba(122, 75, 36, 0.55) 0 1px, transparent 1px 5px);
}
.cert-sheet {
    background: radial-gradient(120% 90% at 50% 40%, #FBF3E2 0%, #F3E5C6 70%, #EAD7AC 100%);
    border: 1px solid #7A4B24;
    box-shadow: inset 0 0 0 3px #F6EAD0, inset 0 0 0 4px rgba(122, 75, 36, 0.45);
}

/* Short screens: keep the whole first screen visible, nothing cut at the bottom edge. */
@media (min-width: 1024px) and (max-height: 760px) {
    .hero .hero-tags { display: none; }
    .hero .hero-grid { padding-top: 1.25rem; padding-bottom: 1.25rem; }
}
@media (min-width: 1024px) and (max-height: 620px) {
    .hero .cert-figure { max-width: 25rem; }
    .hero .cert-note { display: none; }
}

/* Reduce motion override */
@media (prefers-reduced-motion: reduce) {
    .cert-seal { animation: none !important; }
    .cert-check svg { animation: none !important; stroke-dashoffset: 0; }
}
</style>
