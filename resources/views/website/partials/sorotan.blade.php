@php
    // Slides come from the Carousel CMS. With no active slides the section does not render at all.
    $slides = ($carousels ?? collect())->map(fn ($c) => [
        'title' => $c->title,
        'description' => $c->description,
        'buttonText' => $c->button_text,
        'buttonLink' => $c->button_link,
        'image' => $c->image ? Storage::url($c->image) : null,
    ])->values();
@endphp

@if($slides->isNotEmpty())
    <section class="border-t border-line py-20 md:py-28" aria-label="Sorotan"
             x-data="{
                i: 0,
                n: {{ $slides->count() }},
                timer: null,
                reduce: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                go(k) { this.i = (k + this.n) % this.n },
                start() { if (this.n < 2 || this.reduce) return; this.stop(); this.timer = setInterval(() => this.go(this.i + 1), 8000); },
                stop() { clearInterval(this.timer) }
             }"
             x-init="start()" @mouseenter="stop()" @mouseleave="start()" @focusin="stop()" @focusout="start()">
        <div class="wrap">

            <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
                <h2 class="title">Sorotan dari LSP.</h2>

                @if($slides->count() > 1)
                    <div class="flex items-center gap-2" role="group" aria-label="Kontrol sorotan">
                        <button type="button" @click="go(i - 1)" aria-label="Sorotan sebelumnya"
                                class="grid h-11 w-11 cursor-pointer place-items-center rounded-md border border-line text-ink transition-colors hover:bg-sunken">
                            @svg('heroicon-m-arrow-left', 'h-5 w-5')
                        </button>
                        <button type="button" @click="go(i + 1)" aria-label="Sorotan berikutnya"
                                class="grid h-11 w-11 cursor-pointer place-items-center rounded-md border border-line text-ink transition-colors hover:bg-sunken">
                            @svg('heroicon-m-arrow-right', 'h-5 w-5')
                        </button>
                    </div>
                @endif
            </div>

            <div class="mt-10">
                <div class="relative aspect-video overflow-hidden rounded-xl bg-rimba md:aspect-[21/9]">
                    @foreach($slides as $k => $s)
                        <div class="absolute inset-0 transition-opacity duration-500"
                             :class="i === {{ $k }} ? 'opacity-100' : 'opacity-0'"
                             :aria-hidden="i === {{ $k }} ? 'false' : 'true'">
                            @if($s['image'])
                                <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}"
                                     @if($k > 0) loading="lazy" @endif
                                     class="absolute inset-0 h-full w-full object-cover">
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Captions share one grid cell, so the block keeps the height of the tallest one --}}
                <div class="mt-6 grid" aria-live="polite">
                    @foreach($slides as $k => $s)
                        <div class="[grid-area:1/1] transition-opacity duration-500"
                             :class="i === {{ $k }} ? 'opacity-100' : 'pointer-events-none opacity-0'"
                             :aria-hidden="i === {{ $k }} ? 'false' : 'true'">
                            <h3 class="subtitle text-[1.5rem]">{{ $s['title'] }}</h3>
                            @if($s['description'])
                                <p class="mt-2 max-w-[64ch] text-[0.9375rem] leading-relaxed">{{ $s['description'] }}</p>
                            @endif
                            @if($s['buttonText'] && $s['buttonLink'])
                                <a href="{{ $s['buttonLink'] }}"
                                   :tabindex="i === {{ $k }} ? 0 : -1"
                                   class="mt-3 inline-flex min-h-11 items-center text-[0.9375rem] font-bold text-link underline underline-offset-[6px] decoration-link/40 hover:decoration-link">
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
                                    :aria-selected="i === {{ $k }} ? 'true' : 'false'"
                                    aria-label="Sorotan {{ $k + 1 }}: {{ $s['title'] }}"
                                    class="grid h-11 min-w-11 cursor-pointer place-items-center px-1">
                                <span class="block h-1 rounded-full transition-all duration-300"
                                      :class="i === {{ $k }} ? 'w-9 bg-cap' : 'w-5 bg-line'"></span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
