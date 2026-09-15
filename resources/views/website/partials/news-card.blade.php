{{--
    Props:
    $item     (News)
    $featured (bool, optional) – larger treatment for the lead story
--}}
@php
    $featured = $featured ?? false;
    $registry = 'REG-'.str_pad((string) $item->id, 4, '0', STR_PAD_LEFT);
    $date = $item->published_at ?? $item->created_at;
@endphp

<article class="aos-init group flex flex-col bg-paper dark:bg-ink-card border border-hairline
                dark:border-pine-soft rounded-2xl overflow-hidden h-full
                transition-transform duration-200 hover:-translate-y-1">

    <a href="{{ route('news.show', $item->slug) }}" class="flex flex-col h-full">

        {{-- Visual --}}
        <div class="relative overflow-hidden {{ $featured ? 'aspect-video' : 'aspect-3/2' }} bg-mineral dark:bg-pine/30">
            @if($item->image)
                <img src="{{ Storage::url($item->image) }}"
                     alt="{{ $item->title }}"
                     loading="lazy"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            @else
                {{-- No image: show the record's own identity rather than a stock placeholder. --}}
                <div class="absolute inset-0 grid place-items-center" aria-hidden="true">
                    <span class="t-data text-moss/30 dark:text-bone-dim/25 {{ $featured ? 'text-5xl' : 'text-3xl' }}">
                        {{ $registry }}
                    </span>
                </div>
                <div class="absolute inset-0 opacity-[0.07]"
                     style="background-image:radial-gradient(circle,currentColor 1px,transparent 1px);background-size:18px 18px;"
                     aria-hidden="true"></div>
            @endif

            @if($item->category)
                <span class="absolute top-4 left-4 t-micro px-2.5 py-1.5 rounded-md
                             bg-paper/90 dark:bg-ink/85 backdrop-blur-sm text-ink dark:text-bone">
                    {{ $item->category->name }}
                </span>
            @endif
        </div>

        {{-- Body --}}
        <div class="flex flex-col flex-1 p-6 {{ $featured ? 'md:p-8' : '' }}">

            <div class="flex items-center gap-3 t-micro text-moss dark:text-bone-dim mb-4">
                <span class="t-data">{{ $registry }}</span>
                <span class="w-1 h-1 rounded-full bg-marigold" aria-hidden="true"></span>
                <time datetime="{{ $date?->toDateString() }}" class="t-data">
                    {{ $date?->translatedFormat('d M Y') }}
                </time>
            </div>

            <h3 class="{{ $featured ? 't-section text-[1.75rem] md:text-[2.25rem]' : 't-card' }}
                       text-ink dark:text-bone group-hover:text-marigold-deep dark:group-hover:text-marigold-deep dark:hover:text-marigold transition-colors">
                {{ $item->title }}
            </h3>

            <p class="mt-3 text-[0.9375rem] leading-relaxed text-moss dark:text-bone-dim
                      {{ $featured ? 'line-clamp-3' : 'line-clamp-2' }}">
                {{ $item->excerpt }}
            </p>

            <span class="mt-6 pt-5 border-t border-hairline dark:border-pine-soft
                         inline-flex items-center gap-2 t-micro text-ink dark:text-bone">
                Baca selengkapnya
                <svg class="w-3.5 h-3.5 text-marigold-deep dark:text-marigold transition-transform duration-200 group-hover:translate-x-1"
                     viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
    </a>
</article>
