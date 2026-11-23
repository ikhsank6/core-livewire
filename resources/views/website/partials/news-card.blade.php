{{--
    Props:
    $item    (News)
    $variant (string, optional) – 'card' (default) | 'lead' | 'row'
--}}
@php
    $variant = $variant ?? 'card';
    $date = $item->published_at ?? $item->created_at;
    $image = $item->image ? Storage::url($item->image) : null;
@endphp

@if($variant === 'row')
    <article class="group">
        <a href="{{ route('news.show', $item->slug) }}" class="grid grid-cols-[6.5rem_minmax(0,1fr)] sm:grid-cols-[8rem_minmax(0,1fr)] gap-5">
            <div class="relative aspect-4/3 overflow-hidden rounded-lg bg-sunken">
                @if($image)
                    <img src="{{ $image }}" alt="" loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                    <div class="kawung-ink absolute inset-0" aria-hidden="true"></div>
                @endif
            </div>
            <div class="min-w-0 self-center">
                <p class="text-sm text-muted">
                    @if($item->category)<span class="mr-4 font-semibold text-accent-text">{{ $item->category->name }}</span>@endif
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
                    <div class="kawung-ink absolute inset-0" aria-hidden="true"></div>
                @endif
            </div>
            <p class="mt-6 text-sm text-muted">
                @if($item->category)<span class="mr-4 font-semibold text-accent-text">{{ $item->category->name }}</span>@endif
                <time datetime="{{ $date?->toDateString() }}">{{ $date?->translatedFormat('d M Y') }}</time>
            </p>
            <h3 class="title mt-2 text-[clamp(1.75rem,3vw,2.5rem)] group-hover:text-link transition-colors">{{ $item->title }}</h3>
            <p class="mt-4 max-w-[58ch] leading-relaxed line-clamp-3">{{ $item->excerpt }}</p>
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
                    <div class="kawung-ink absolute inset-0" aria-hidden="true"></div>
                @endif
            </div>
            <p class="mt-5 text-sm text-muted">
                @if($item->category)<span class="mr-4 font-semibold text-accent-text">{{ $item->category->name }}</span>@endif
                <time datetime="{{ $date?->toDateString() }}">{{ $date?->translatedFormat('d M Y') }}</time>
            </p>
            <h3 class="subtitle mt-2 group-hover:text-link transition-colors line-clamp-2">{{ $item->title }}</h3>
            <p class="mt-3 text-[0.9375rem] leading-relaxed line-clamp-2">{{ $item->excerpt }}</p>
        </a>
    </article>
@endif
