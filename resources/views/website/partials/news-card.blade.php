{{--
    Props:
    $item     (News model)
    $featured (bool, optional) – larger horizontal card layout
--}}
@php $featured = $featured ?? false; @endphp

@if($featured)
{{-- ── Featured card: horizontal layout ── --}}
<article class="group bg-white dark:bg-dark-card rounded-2xl overflow-hidden border border-slate-100 dark:border-dark-border shadow-sm hover:shadow-lg transition-all duration-300">
    <div class="grid md:grid-cols-2">
        {{-- Image --}}
        <div class="relative overflow-hidden h-64 md:h-auto min-h-[280px]">
            @if($item->image)
                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-linear-to-br from-primary/15 to-accent/15 dark:from-primary/10 dark:to-accent/10 flex items-center justify-center">
                    <svg class="w-16 h-16 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            @endif
            {{-- Date badge --}}
            <div class="absolute top-4 left-4 bg-primary text-white text-center px-3 py-2 rounded-xl shadow-md">
                <span class="font-bold text-xl block leading-none">{{ $item->published_at?->format('d') }}</span>
                <span class="text-xs uppercase tracking-wide opacity-90">{{ $item->published_at?->format('M') }}</span>
            </div>
            @if($item->category)
                <div class="absolute bottom-4 left-4">
                    <span class="px-3 py-1 bg-white/90 dark:bg-dark-card/90 backdrop-blur-sm text-primary text-xs font-bold rounded-full">
                        {{ $item->category->name }}
                    </span>
                </div>
            @endif
        </div>
        {{-- Content --}}
        <div class="p-6 md:p-8 flex flex-col justify-center">
            <span class="text-xs text-slate-400 dark:text-slate-500 mb-3 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $item->published_at?->diffForHumans() }}
            </span>
            <h2 class="font-bold text-slate-900 dark:text-white text-2xl mb-3 group-hover:text-primary transition-colors duration-300 line-clamp-2">
                <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 line-clamp-3 leading-relaxed">
                {{ Str::limit($item->excerpt ?? strip_tags($item->content), 180) }}
            </p>
            <a href="{{ route('news.show', $item->slug) }}"
               class="inline-flex items-center gap-2 text-primary font-semibold text-sm group/link">
                Baca Selengkapnya
                <svg class="w-4 h-4 transition-transform duration-300 group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</article>

@else
{{-- ── Standard card: vertical layout ── --}}
<article class="group bg-white dark:bg-dark-card rounded-2xl overflow-hidden border border-slate-100 dark:border-dark-border shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
    {{-- Image --}}
    <div class="relative overflow-hidden h-48">
        @if($item->image)
            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full bg-linear-to-br from-slate-100 to-slate-200 dark:from-dark dark:to-dark-card flex items-center justify-center">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
        @endif
        {{-- Date badge --}}
        <div class="absolute top-3 left-3 bg-white/95 dark:bg-dark-card/95 backdrop-blur-sm px-2.5 py-1.5 rounded-lg text-center shadow-sm">
            <span class="font-bold text-primary text-sm block leading-none">{{ $item->published_at?->format('d') }}</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase">{{ $item->published_at?->format('M') }}</span>
        </div>
    </div>
    {{-- Content --}}
    <div class="p-5">
        @if($item->category)
            <span class="inline-block px-2.5 py-0.5 bg-primary/10 dark:bg-primary/20 text-primary text-xs font-semibold rounded-md mb-3">
                {{ $item->category->name }}
            </span>
        @endif
        <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-2 group-hover:text-primary transition-colors duration-300 line-clamp-2">
            <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
        </h3>
        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-2 leading-relaxed">
            {{ Str::limit($item->excerpt ?? strip_tags($item->content), 100) }}
        </p>
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-400 dark:text-slate-500">{{ $item->published_at?->diffForHumans() }}</span>
            <a href="{{ route('news.show', $item->slug) }}"
               class="inline-flex items-center gap-1.5 text-primary font-medium text-sm group/link">
                Baca
                <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</article>
@endif
