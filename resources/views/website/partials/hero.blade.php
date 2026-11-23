@php
    /**
     * The arch ("the door") shows the slides managed in the Carousel CMS.
     * Headline and body copy belong to the portal itself, not to a slide.
     */
    $slides = ($carousels ?? collect())->map(fn ($c) => [
        'title' => $c->title,
        'description' => $c->description,
        'buttonText' => $c->button_text,
        'buttonLink' => $c->button_link,
        'image' => $c->image ? Storage::url($c->image) : null,
    ])->values();

    // The door never renders empty when the CMS has no slides.
    if ($slides->isEmpty()) {
        $slides = collect([[
            'title' => 'Satu pintu untuk semua urusan warga',
            'description' => 'Izin, dokumen, dan informasi resmi dalam satu alur yang bisa dilacak.',
            'buttonText' => null,
            'buttonLink' => null,
            'image' => null,
        ]]);
    }

    $registerUrl = Route::has('auth.register') ? route('auth.register') : route('login');

    $office = collect([
        ['label' => 'Kantor layanan', 'value' => $aboutUs?->address],
        ['label' => 'Telepon',        'value' => $aboutUs?->phone],
    ])->filter(fn ($f) => filled($f['value']));
@endphp

<section class="on-dark relative isolate overflow-hidden bg-nila text-white"
         x-data="{
            slide: 0,
            n: {{ $slides->count() }},
            timer: null,
            reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
            go(k) { this.slide = (k + this.n) % this.n },
            start() { if (this.n < 2 || this.reduce) return; this.stop(); this.timer = setInterval(() => this.go(this.slide + 1), 7000); },
            stop() { clearInterval(this.timer) },
            light(e) {
                if (this.reduce) return;
                const r = this.$el.getBoundingClientRect();
                this.$refs.lit.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                this.$refs.lit.style.setProperty('--my', (e.clientY - r.top) + 'px');
            }
         }"
         x-init="start()"
         @pointermove="light($event)">

    {{-- Ground: faint kawung motif. A bright copy is revealed around the
         pointer (or beside the door when there is no pointer). --}}
    <div class="kawung absolute inset-0 -z-10" aria-hidden="true"></div>
    <div x-ref="lit" class="kawung-lit absolute inset-0 -z-10" aria-hidden="true"></div>

    <div class="wrap grid items-center gap-14 lg:grid-cols-[minmax(0,1.08fr)_minmax(0,0.92fr)] py-14 md:py-20 lg:py-20">

        {{-- Copy --}}
        <div>
            <h1 class="display text-white max-w-[19ch]">
                Urus izin dan dokumen dari satu pintu.
            </h1>

            <p class="lead mt-7 max-w-[52ch] text-white/75">
                Ajukan perizinan, dokumen kependudukan, dan pengaduan dari satu akun.
                Setiap berkas punya nomor registrasi, jadi Anda bisa memeriksa posisinya kapan saja tanpa datang ke loket.
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-x-7 gap-y-4">
                <a href="{{ $registerUrl }}" class="ticket ticket-lg">Ajukan layanan</a>
                <a href="#alur"
                   class="inline-flex items-center min-h-11 text-[0.9375rem] font-semibold text-white underline decoration-white/40 underline-offset-[6px] hover:decoration-kunyit transition-colors">
                    Lihat perjalanan sebuah berkas
                </a>
            </div>

            @if($office->isNotEmpty())
                <dl class="mt-14 grid gap-x-10 gap-y-5 sm:grid-cols-2 max-w-xl border-t border-white/15 pt-6">
                    @foreach($office as $fact)
                        <div>
                            <dt class="text-sm text-white/60">{{ $fact['label'] }}</dt>
                            <dd class="mt-1 text-[0.9375rem] font-medium text-white wrap-break-word">{{ $fact['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>

        {{-- The door --}}
        <div class="relative mx-auto w-full max-w-sm lg:max-w-[21rem]">
            <div class="door-spill absolute -inset-x-12 -bottom-12 h-4/5 pointer-events-none" aria-hidden="true"></div>

            <div class="relative">
                {{-- Door leaf: a second arch, offset, as if the door stands ajar --}}
                <div class="arch absolute inset-0 translate-x-4 border border-white/25" aria-hidden="true"></div>

                <div class="arch relative aspect-4/5 overflow-hidden border-2 border-kunyit bg-[#06113A]"
                     @mouseenter="stop()" @mouseleave="start()" @focusin="stop()" @focusout="start()"
                     role="group" aria-roledescription="carousel" aria-label="Sorotan layanan">
                    @foreach($slides as $k => $s)
                        <div class="absolute inset-0 transition-opacity duration-500"
                             :class="slide === {{ $k }} ? 'opacity-100' : 'opacity-0'"
                             :aria-hidden="slide === {{ $k }} ? 'false' : 'true'">
                            @if($s['image'])
                                <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}"
                                     @if($k > 0) loading="lazy" @endif
                                     class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 grid place-items-end p-8 bg-linear-to-b from-kobalt to-nila"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Caption stack: every slide's caption occupies the same cell, so the
                 block keeps the height of the tallest one and nothing jumps. --}}
            <div class="mt-6 grid" aria-live="polite">
                @foreach($slides as $k => $s)
                    <div class="[grid-area:1/1] transition-opacity duration-500"
                         :class="slide === {{ $k }} ? 'opacity-100' : 'opacity-0 pointer-events-none'"
                         :aria-hidden="slide === {{ $k }} ? 'false' : 'true'">
                        <p class="subtitle text-white">{{ $s['title'] }}</p>
                        @if($s['description'])
                            <p class="mt-1.5 text-[0.9375rem] leading-relaxed text-white/70 line-clamp-2">{{ $s['description'] }}</p>
                        @endif
                        @if($s['buttonText'] && $s['buttonLink'])
                            <a href="{{ $s['buttonLink'] }}"
                               :tabindex="slide === {{ $k }} ? 0 : -1"
                               class="mt-3 inline-flex items-center min-h-11 text-[0.9375rem] font-semibold text-kunyit underline underline-offset-[6px] decoration-kunyit/40 hover:decoration-kunyit">
                                {{ $s['buttonText'] }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($slides->count() > 1)
                <div class="mt-2 flex items-center -ml-1" role="tablist" aria-label="Pilih sorotan">
                    @foreach($slides as $k => $s)
                        <button type="button" role="tab" @click="go({{ $k }})"
                                :aria-selected="slide === {{ $k }} ? 'true' : 'false'"
                                aria-label="Sorotan {{ $k + 1 }}: {{ $s['title'] }}"
                                class="h-11 px-1 grid place-items-center cursor-pointer">
                            <span class="block h-1 rounded-full transition-all duration-300"
                                  :class="slide === {{ $k }} ? 'w-9 bg-kunyit' : 'w-5 bg-white/30'"></span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
