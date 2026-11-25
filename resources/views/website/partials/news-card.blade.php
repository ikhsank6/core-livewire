{{--
    Props:
    $item    (News)
    $variant (string, optional) – 'card' (default) | 'lead' | 'row'
--}}
@php
    $variant = $variant ?? 'card';
    $date = $item->published_at ?? $item->created_at;
    $image = $item->image ? Storage::url($item->image) : null;
    $catSlug = $item->category?->slug;
    $calendar = 'M8 7V3m8 4V3M5 11h14M6 5h12a1 1 0 011 1v13a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z';
@endphp

@if($variant === 'row')
    <article class="group">
        <a href="{{ route('news.show', $item->slug) }}" class="grid grid-cols-[6.5rem_minmax(0,1fr)] sm:grid-cols-[8rem_minmax(0,1fr)] gap-5">
            <div class="relative aspect-4/3 overflow-hidden rounded-lg bg-sunken">
                @if($image)
                    <img src="{{ $image }}" alt="" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                    <div class="rosette inset-0 m-auto w-4/5 text-ink/[0.08]" aria-hidden="true"></div>
                    <span class="absolute inset-0 grid place-items-center text-brand/60">
                        @include('website.partials.news-icon', ['slug' => $catSlug, 'class' => 'h-14 w-14'])
                    </span>
                @endif
            </div>
            <div class="min-w-0 self-center">
                <p class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-muted">
                    @if($item->category)
                        <span class="inline-flex items-center gap-1.5 font-semibold text-accent-text">
                            @include('website.partials.news-icon', ['slug' => $catSlug, 'class' => 'h-4 w-4 shrink-0'])
                            {{ $item->category->name }}
                        </span>
                    @endif
                    <time datetime="{{ $date?->toDateString() }}">{{ $date?->translatedFormat('d M Y') }}</time>
                </p>
                <h3 class="subtitle mt-1.5 text-[1.125rem] group-hover:text-link transition-colors line-clamp-3">{{ $item->title }}</h3>
            </div>
        </a>
    </article>

@elseif($variant === 'lead')
    <article class="group h-full">
        <a href="{{ route('news.show', $item->slug) }}" class="flex h-full flex-col">
            <div class="relative aspect-16/10 overflow-hidden rounded-2xl bg-sunken">
                @if($image)
                    <img src="{{ $image }}" alt="{{ $item->title }}" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                @else
                    <div class="rosette inset-0 m-auto w-4/5 text-ink/[0.08]" aria-hidden="true"></div>
                    <span class="absolute inset-0 grid place-items-center text-brand/60">
                        @include('website.partials.news-icon', ['slug' => $catSlug, 'class' => 'h-14 w-14'])
                    </span>
                @endif
                @if($item->category)
                    <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-md bg-navy/90 px-2.5 py-1 text-xs font-bold text-white shadow-xs ring-1 ring-white/15 backdrop-blur-sm">
                        @include('website.partials.news-icon', ['slug' => $catSlug, 'class' => 'h-3.5 w-3.5 text-gold-soft'])
                        {{ $item->category->name }}
                    </span>
                @endif
            </div>
            <p class="mt-6 flex items-center gap-1.5 text-sm text-muted">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $calendar }}"/></svg>
                <time datetime="{{ $date?->toDateString() }}">{{ $date?->translatedFormat('d M Y') }}</time>
            </p>
            <h3 class="title mt-2 text-[clamp(1.75rem,3vw,2.5rem)] group-hover:text-link transition-colors">{{ $item->title }}</h3>
            <p class="mt-4 max-w-[58ch] leading-relaxed line-clamp-3">{{ $item->excerpt }}</p>
            <span class="mt-auto inline-flex items-center gap-1.5 pt-4 text-[0.9375rem] font-bold text-link">
                Baca selengkapnya
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-6-6l6 6-6 6"/></svg>
                <span class="sr-only">: {{ $item->title }}</span>
            </span>
        </a>
    </article>

@else
    <article class="group h-full">
        <a href="{{ route('news.show', $item->slug) }}" class="flex h-full flex-col">
            <div class="relative aspect-16/10 overflow-hidden rounded-xl bg-sunken">
                @if($image)
                    <img src="{{ $image }}" alt="{{ $item->title }}" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                    <div class="rosette inset-0 m-auto w-4/5 text-ink/[0.08]" aria-hidden="true"></div>
                    <span class="absolute inset-0 grid place-items-center text-brand/60">
                        @include('website.partials.news-icon', ['slug' => $catSlug, 'class' => 'h-14 w-14'])
                    </span>
                @endif
                @if($item->category)
                    <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-md bg-navy/90 px-2.5 py-1 text-xs font-bold text-white shadow-xs ring-1 ring-white/15 backdrop-blur-sm">
                        @include('website.partials.news-icon', ['slug' => $catSlug, 'class' => 'h-3.5 w-3.5 text-gold-soft'])
                        {{ $item->category->name }}
                    </span>
                @endif
            </div>
            <p class="mt-5 flex items-center gap-1.5 text-sm text-muted">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $calendar }}"/></svg>
                <time datetime="{{ $date?->toDateString() }}">{{ $date?->translatedFormat('d M Y') }}</time>
            </p>
            <h3 class="subtitle mt-2 group-hover:text-link transition-colors line-clamp-2">{{ $item->title }}</h3>
            <p class="mt-3 text-[0.9375rem] leading-relaxed line-clamp-2">{{ $item->excerpt }}</p>
            <span class="mt-auto inline-flex items-center gap-1.5 pt-4 text-[0.9375rem] font-bold text-link">
                Baca selengkapnya
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1 motion-reduce:transition-none" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-6-6l6 6-6 6"/></svg>
                <span class="sr-only">: {{ $item->title }}</span>
            </span>
        </a>
    </article>
@endif
