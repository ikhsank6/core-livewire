{{--
    Props:
    $item     (News model)
    $featured (bool, optional) – larger horizontal card layout
--}}
@php $featured = $featured ?? false; @endphp

@if($featured)
{{-- ── Featured card: horizontal layout ── --}}
<article class="group card-base flex flex-col md:flex-row overflow-hidden">
    {{-- Image --}}
    <div class="relative md:w-2/5 shrink-0 overflow-hidden min-h-[260px] md:min-h-auto">
        @if($item->image)
            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                 class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 group-hover:scale-105">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-accent/15 flex items-center justify-center">
                <svg class="w-16 h-16 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
        @endif
        {{-- Overlay --}}
        <div class="absolute inset-0 news-img-overlay opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        {{-- Category badge --}}
        @if($item->category)
            <div class="absolute top-4 left-4">
                <span class="px-3 py-1.5 bg-primary text-white text-xs font-bold rounded-full shadow-md font-heading tracking-wide">
                    {{ $item->category->name }}
                </span>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex flex-col justify-center p-7 md:p-8 flex-1">
        {{-- Meta --}}
        <div class="flex items-center gap-3 mb-4">
            <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $item->published_at?->translatedFormat('d M Y') }}
            </div>
            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
            <span class="text-xs text-slate-400 dark:text-slate-500">
                {{ ceil(str_word_count(strip_tags($item->content ?? '')) / 200) }} mnt baca
            </span>
        </div>

        <h2 class="font-heading font-bold text-slate-900 dark:text-white text-xl md:text-2xl mb-3 group-hover:text-primary dark:group-hover:text-primary-light transition-colors duration-200 line-clamp-2 leading-snug">
            <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
        </h2>

        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-5 line-clamp-3">
            {{ Str::limit($item->excerpt ?? strip_tags($item->content), 200) }}
        </p>

        <a href="{{ route('news.show', $item->slug) }}"
           class="inline-flex items-center gap-2 text-primary font-semibold text-sm font-heading group/link">
            Baca Selengkapnya
            <svg class="w-4 h-4 transition-transform duration-300 group-hover/link:translate-x-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
    </div>
</article>

@else
{{-- ── Standard card: vertical layout ── --}}
<article class="group card-base flex flex-col">
    {{-- Image --}}
    <div class="relative overflow-hidden h-52 shrink-0">
        @if($item->image)
            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-108">
        @else
            <div class="w-full h-full bg-gradient-to-br from-primary/10 via-primary/5 to-accent/10 flex items-center justify-center">
                <svg class="w-12 h-12 text-primary/25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
        @endif
        {{-- Bottom gradient overlay --}}
        <div class="news-img-overlay absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        {{-- Category badge --}}
        @if($item->category)
            <div class="absolute top-3 left-3">
                <span class="px-2.5 py-1 bg-primary text-white text-[11px] font-bold rounded-full shadow font-heading tracking-wide">
                    {{ $item->category->name }}
                </span>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex flex-col flex-1 p-5">
        {{-- Meta --}}
        <div class="flex items-center gap-2 mb-3">
            <span class="text-xs text-slate-400 dark:text-slate-500">
                {{ $item->published_at?->translatedFormat('d M Y') }}
            </span>
            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
            <span class="text-xs text-slate-400 dark:text-slate-500">
                {{ ceil(str_word_count(strip_tags($item->content ?? '')) / 200) }} mnt
            </span>
        </div>

        <h3 class="font-heading font-bold text-slate-900 dark:text-white text-base mb-2 group-hover:text-primary dark:group-hover:text-primary-light transition-colors duration-200 line-clamp-2 leading-snug flex-1">
            <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
        </h3>

        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-4 line-clamp-2">
            {{ Str::limit($item->excerpt ?? strip_tags($item->content), 120) }}
        </p>

        <div class="mt-auto pt-4 border-t border-slate-100 dark:border-dark-border flex items-center justify-between">
            <span class="text-xs text-slate-400 dark:text-slate-500">
                {{ $item->published_at?->diffForHumans() }}
            </span>
            <a href="{{ route('news.show', $item->slug) }}"
               class="inline-flex items-center gap-1.5 text-primary font-semibold text-sm font-heading group/link">
                Baca
                <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</article>
@endif
