@php
    /**
     * Hero slides come from the Carousel CMS. The accent word is the final
     * word of each title, lifted into marigold — a deliberate, consistent
     * rule rather than a per-slide decision.
     */
    $slides = ($carousels ?? collect())->map(function ($c) {
        $words = preg_split('/\s+/', trim($c->title));
        $last = array_pop($words);

        return [
            'titleHtml' => e(implode(' ', $words))
                .' <span class="text-marigold">'.e($last).'</span>',
            'description' => $c->description,
            'buttonText' => $c->button_text,
            'buttonLink' => $c->button_link,
            'image' => $c->image ? Storage::url($c->image) : null,
        ];
    })->values();

    // Fallback so the hero never renders empty if the CMS has no slides.
    if ($slides->isEmpty()) {
        $slides = collect([[
            'titleHtml' => 'Satu pintu untuk semua urusan <span class="text-marigold">warga</span>',
            'description' => 'Izin, dokumen, dan informasi resmi dalam satu alur yang bisa dilacak dari awal sampai terbit.',
            'buttonText' => 'Ajukan layanan',
            'buttonLink' => route('login'),
            'image' => null,
        ]]);
    }

    $stats = [
        ['value' => 1248,  'label' => 'Layanan aktif'],
        ['value' => 56,    'label' => 'Instansi terhubung'],
        ['value' => 12430, 'label' => 'Pengajuan bulan ini'],
    ];
@endphp

<section class="relative bg-ink text-bone rounded-b-3xl md:rounded-b-[2.5rem] overflow-hidden
                dark:border-b dark:border-pine-soft"
         x-data="{
            i: 0,
            fading: false,
            slides: {{ Illuminate\Support\Js::from($slides) }},
            timer: null,
            go(n) {
                if (n === this.i) return;
                this.fading = true;
                setTimeout(() => { this.i = n; this.fading = false; }, 320);
            },
            next() { this.go((this.i + 1) % this.slides.length); },
            start() {
                if (this.slides.length < 2) return;
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
                this.timer = setInterval(() => this.next(), 7000);
            },
            stop() { if (this.timer) clearInterval(this.timer); }
         }"
         x-init="start()"
         @mouseenter="stop()" @mouseleave="start()">

    {{-- Ground texture: blurred and desaturated so any operator-uploaded photo
         reads as texture, never as legible content behind the headline. --}}
    <div aria-hidden="true"
         class="absolute inset-0 bg-cover bg-center opacity-[0.10] grayscale blur-[18px] scale-110 transition-opacity duration-500"
         :style="slides[i].image ? `background-image:url('${slides[i].image}')` : ''"></div>
    <div aria-hidden="true"
         class="absolute inset-0 bg-linear-to-b from-ink/70 via-ink/35 to-ink"></div>

    <div class="relative mx-auto max-w-300 px-5 md:px-8 pt-32 pb-28 md:pt-40 md:pb-32 min-h-[88dvh] flex flex-col justify-center">

        <span class="t-micro text-marigold flex items-center gap-2.5 mb-8">
            <span class="w-5 h-px bg-marigold"></span>
            Portal Layanan Publik Terpadu
        </span>

        <div class="transition-opacity duration-300" :class="fading ? 'opacity-0' : 'opacity-100'">
            <h1 class="t-display max-w-[16ch]" x-html="slides[i].titleHtml">{!! $slides[0]['titleHtml'] !!}</h1>

            <p class="t-lead mt-7 text-bone-dim" x-text="slides[i].description">{{ $slides[0]['description'] }}</p>

            <div class="mt-10 flex flex-wrap items-center gap-3">
                <a :href="slides[i].buttonLink || '{{ route('login') }}'"
                   href="{{ $slides[0]['buttonLink'] ?: route('login') }}"
                   class="inline-flex items-center gap-2.5 h-13 pl-7 pr-6 rounded-full bg-marigold text-ink
                          hover:bg-marigold-dim transition-colors t-micro">
                    <span x-text="slides[i].buttonText || 'Ajukan layanan'">{{ $slides[0]['buttonText'] ?: 'Ajukan layanan' }}</span>
                    <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <a href="{{ route('about') }}"
                   class="inline-flex items-center gap-2.5 h-13 px-7 rounded-full border border-bone/20
                          text-bone hover:bg-bone/10 transition-colors t-micro">
                    Lihat profil
                </a>
            </div>
        </div>

        {{-- Slide indicators --}}
        @if($slides->count() > 1)
            <div class="mt-12 flex items-center gap-2.5" role="tablist" aria-label="Pilih sorotan">
                @foreach($slides as $index => $slide)
                    <button type="button" role="tab"
                            @click="go({{ $index }})"
                            :aria-selected="i === {{ $index }} ? 'true' : 'false'"
                            aria-label="Sorotan {{ $index + 1 }}"
                            class="h-1.5 rounded-full transition-all duration-300"
                            :class="i === {{ $index }} ? 'w-9 bg-marigold' : 'w-4 bg-bone/25 hover:bg-bone/45'"></button>
                @endforeach
            </div>
        @endif

        {{-- Stat band --}}
        <div class="mt-14 pt-10 border-t border-bone/10 grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-6">
            @foreach($stats as $stat)
                <div>
                    {{-- The true figure is server-rendered so it survives no-JS and
                         never-observed; the count-up is the enhancement. --}}
                    <div class="t-figure text-bone" data-counter data-target="{{ $stat['value'] }}">{{ number_format($stat['value'], 0, ',', '.') }}</div>
                    <div class="t-micro text-bone-dim mt-2.5">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
