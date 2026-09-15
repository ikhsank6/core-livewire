@php
    /**
     * Register rail — the signature element.
     *
     * Straddles the seam between the ink hero and the mineral ground. Each row
     * is a real News record: registry number derived from the primary key,
     * timestamp from published_at, category from the relation. Nothing here is
     * invented per render.
     */
    $railItems = ($news ?? collect())->take(5);

    $registryNo = fn ($item) => 'REG-'.str_pad((string) $item->id, 4, '0', STR_PAD_LEFT);
    $stamp = function ($item) {
        $at = $item->published_at ?? $item->created_at;
        if (! $at) {
            return '--:--';
        }
        return $at->isToday() ? $at->format('H:i') : $at->format('d M');
    };
@endphp

<div class="relative z-10 -mt-14 md:-mt-16">
    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="rail bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft
                    rounded-2xl shadow-[0_18px_50px_-24px_rgba(12,26,21,0.45)] overflow-hidden">

            {{-- Rail header --}}
            <div class="flex items-center justify-between gap-4 px-5 md:px-7 py-4
                        border-b border-hairline dark:border-pine-soft">
                <span class="t-micro text-moss dark:text-bone-dim flex items-center gap-2.5">
                    <span class="relative flex w-2 h-2" aria-hidden="true">
                        <span class="absolute inline-flex w-full h-full rounded-full bg-marigold opacity-60"></span>
                        <span class="relative inline-flex w-2 h-2 rounded-full bg-marigold"></span>
                    </span>
                    Registrasi masuk
                </span>
                <a href="{{ route('news.index') }}"
                   class="t-micro text-moss dark:text-bone-dim hover:text-ink dark:hover:text-bone
                          transition-colors inline-flex items-center gap-2">
                    Semua
                    <svg class="w-3 h-3" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            @if($railItems->isNotEmpty())
                <div class="rail-viewport">
                    <div class="rail-track">
                        {{-- Primary copy: the real, focusable rows. --}}
                        @foreach($railItems as $index => $item)
                            <a href="{{ route('news.show', $item->slug) }}"
                               class="group flex items-center gap-3 md:gap-5 px-5 md:px-7 h-15
                                      border-b border-hairline/70 dark:border-pine-soft/70
                                      hover:bg-mineral dark:hover:bg-pine/30 transition-colors
                                      {{ $index >= 3 ? 'hidden md:flex' : 'flex' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-marigold shrink-0" aria-hidden="true"></span>

                                <span class="t-data text-[0.6875rem] text-moss dark:text-bone-dim shrink-0">
                                    {{ $registryNo($item) }}
                                </span>

                                <span class="t-data text-[0.6875rem] text-moss/65 dark:text-bone-dim/65 shrink-0 hidden sm:inline">
                                    {{ $stamp($item) }}
                                </span>

                                @if($item->category)
                                    <span class="t-micro shrink-0 hidden md:inline px-2.5 py-1 rounded-md
                                                 bg-mineral dark:bg-pine/40 text-moss dark:text-bone-dim">
                                        {{ $item->category->name }}
                                    </span>
                                @endif

                                <span class="flex-1 truncate text-[0.9375rem] text-ink dark:text-bone">
                                    {{ $item->title }}
                                </span>

                                <svg class="w-3.5 h-3.5 shrink-0 text-moss/50 dark:text-bone-dim/50
                                            group-hover:text-marigold transition-colors"
                                     viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                     stroke-width="1.8" aria-hidden="true">
                                    <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        @endforeach

                        {{-- Duplicate half: makes translateY(-50%) wrap seamlessly.
                             Hidden from assistive tech and removed from the tab order. --}}
                        <div class="rail-duplicate" aria-hidden="true">
                            @foreach($railItems as $item)
                                <span class="flex items-center gap-3 md:gap-5 px-5 md:px-7 h-15
                                             border-b border-hairline/70 dark:border-pine-soft/70">
                                    <span class="w-1.5 h-1.5 rounded-full bg-marigold shrink-0"></span>
                                    <span class="t-data text-[0.6875rem] text-moss dark:text-bone-dim shrink-0">
                                        {{ $registryNo($item) }}
                                    </span>
                                    <span class="t-data text-[0.6875rem] text-moss/65 dark:text-bone-dim/65 shrink-0 hidden sm:inline">
                                        {{ $stamp($item) }}
                                    </span>
                                    @if($item->category)
                                        <span class="t-micro shrink-0 hidden md:inline px-2.5 py-1 rounded-md
                                                     bg-mineral dark:bg-pine/40 text-moss dark:text-bone-dim">
                                            {{ $item->category->name }}
                                        </span>
                                    @endif
                                    <span class="flex-1 truncate text-[0.9375rem] text-ink dark:text-bone">
                                        {{ $item->title }}
                                    </span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty state: the rail stays, and invites action. --}}
                <a href="{{ route('news.index') }}"
                   class="flex items-center gap-4 px-5 md:px-7 h-15 hover:bg-mineral dark:hover:bg-pine/30 transition-colors">
                    <span class="t-data text-[0.6875rem] text-moss dark:text-bone-dim">REG-0000</span>
                    <span class="flex-1 text-[0.9375rem] text-moss dark:text-bone-dim">
                        Belum ada registrasi masuk. Buka halaman berita untuk arsip sebelumnya.
                    </span>
                </a>
            @endif
        </div>
    </div>
</div>
