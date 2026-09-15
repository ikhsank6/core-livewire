@extends('layouts.website')

@section('title', $news->title . ' — ' . ($aboutUs->company_name ?? config('app.name')))
@section('description', $news->excerpt ?? Str::limit(strip_tags($news->content), 160))

@php
    $registry = 'REG-'.str_pad((string) $news->id, 4, '0', STR_PAD_LEFT);
    $date = $news->published_at ?? $news->created_at;

    $shares = [
        ['label' => 'Bagikan ke Facebook', 'url' => 'https://www.facebook.com/sharer/sharer.php?u='.urlencode(request()->url()), 'path' => 'M14 8.5V6.8c0-.8.2-1.3 1.4-1.3H17V2.6c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.2H8.6v3h2.5V19H14v-7.5h2.5l.4-3H14Z'],
        ['label' => 'Bagikan ke X',        'url' => 'https://twitter.com/intent/tweet?url='.urlencode(request()->url()).'&text='.urlencode($news->title), 'path' => 'M15.2 2.9h2.6l-5.7 6.5 6.7 8.8h-5.2L9.5 13l-4.7 5.2H2.2l6.1-7-6.4-8.3h5.4l3.7 4.9 4.2-4.9Zm-.9 13.8h1.4L6.1 4.3H4.6l9.7 12.4Z'],
        ['label' => 'Bagikan via WhatsApp','url' => 'https://wa.me/?text='.urlencode($news->title.' '.request()->url()), 'path' => 'M10 2a8 8 0 0 0-6.9 12L2 18l4.1-1.1A8 8 0 1 0 10 2Zm4.3 11.2c-.2.5-1 1-1.5 1.1-.4 0-.9.1-1.4-.1-.3-.1-.8-.3-1.4-.5-2.4-1.1-4-3.5-4.1-3.7-.1-.2-1-1.3-1-2.4 0-1.2.6-1.7.8-2 .2-.2.5-.3.6-.3h.5c.1 0 .3 0 .5.4l.7 1.7c0 .1.1.3 0 .4l-.3.4-.3.3c-.1.1-.2.2-.1.4.1.2.5 1 1.2 1.6.8.7 1.5 1 1.7 1.1.2.1.3 0 .4-.1l.6-.7c.1-.2.3-.1.4-.1l1.6.8c.2.1.4.2.4.3v.5Z'],
        ['label' => 'Bagikan ke LinkedIn', 'url' => 'https://www.linkedin.com/shareArticle?mini=true&url='.urlencode(request()->url()).'&title='.urlencode($news->title), 'path' => 'M6.1 17.2H3.2V7.6h2.9v9.6ZM4.6 6.3a1.7 1.7 0 1 1 0-3.4 1.7 1.7 0 0 1 0 3.4Zm12.6 10.9h-2.9v-4.7c0-1.1 0-2.5-1.5-2.5s-1.8 1.2-1.8 2.4v4.8H8.1V7.6H11v1.3h.1c.4-.8 1.4-1.6 2.8-1.6 3 0 3.5 2 3.5 4.5v5.4Z'],
    ];
@endphp

@section('content')

{{-- The page header carries the only <h1>; the article body must not repeat it. --}}
@include('website.partials.page-header', [
    'title'      => $news->title,
    'breadcrumb' => $news->category->name ?? 'Berita',
    'bgImage'    => $news->image ? Storage::url($news->image) : null,
])

<section class="py-16 md:py-24">
    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,20rem)] gap-8 lg:gap-12">

            <div>
                <article class="bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft
                                rounded-2xl overflow-hidden">

                    @if($news->image)
                        <img src="{{ Storage::url($news->image) }}"
                             alt="{{ $news->title }}"
                             class="w-full aspect-video object-cover">
                    @endif

                    <div class="p-6 md:p-10">

                        {{-- Record meta --}}
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2.5 pb-6 mb-8
                                    border-b border-hairline dark:border-pine-soft">
                            <span class="t-data text-[0.6875rem] text-moss dark:text-bone-dim">{{ $registry }}</span>
                            <span class="w-1 h-1 rounded-full bg-marigold" aria-hidden="true"></span>
                            <time datetime="{{ $date?->toDateString() }}"
                                  class="t-data text-[0.6875rem] text-moss dark:text-bone-dim">
                                {{ $date?->translatedFormat('d M Y') }}
                            </time>
                            @if($news->category)
                                <span class="t-micro px-2.5 py-1 rounded-md bg-mineral dark:bg-pine/40
                                             text-moss dark:text-bone-dim">
                                    {{ $news->category->name }}
                                </span>
                            @endif
                        </div>

                        @if($news->excerpt)
                            <p class="t-lead mb-8 text-ink dark:text-bone">{{ $news->excerpt }}</p>
                        @endif

                        <div class="prose">{!! $news->content !!}</div>

                        {{-- Share --}}
                        <div class="mt-10 pt-7 border-t border-hairline dark:border-pine-soft
                                    flex flex-wrap items-center gap-4">
                            <span class="t-micro text-moss dark:text-bone-dim">Bagikan</span>
                            <ul class="flex items-center gap-2">
                                @foreach($shares as $share)
                                    <li>
                                        <a href="{{ $share['url'] }}" target="_blank" rel="noopener noreferrer"
                                           aria-label="{{ $share['label'] }}"
                                           class="w-11 h-11 rounded-full grid place-items-center border
                                                  border-hairline dark:border-pine-soft text-moss dark:text-bone-dim
                                                  hover:bg-marigold hover:border-marigold hover:text-ink transition-colors">
                                            <svg class="w-4.5 h-4.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="{{ $share['path'] }}"/>
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </article>

                {{-- Related --}}
                @if($relatedNews->count() > 0)
                    <div class="mt-12">
                        <h2 class="t-card text-ink dark:text-bone mb-5">Catatan terkait</h2>

                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($relatedNews as $related)
                                <li>
                                    <a href="{{ route('news.show', $related->slug) }}"
                                       class="group flex gap-4 h-full p-5 bg-paper dark:bg-ink-card border
                                              border-hairline dark:border-pine-soft rounded-2xl
                                              hover:border-marigold transition-colors">
                                        <span class="t-data text-[0.6875rem] text-moss/60 dark:text-bone-dim/60 shrink-0 pt-0.5">
                                            {{ 'REG-'.str_pad((string) $related->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block text-[0.9375rem] leading-snug text-ink dark:text-bone
                                                         group-hover:text-marigold-deep dark:group-hover:text-marigold-deep dark:hover:text-marigold transition-colors line-clamp-2">
                                                {{ $related->title }}
                                            </span>
                                            <time datetime="{{ ($related->published_at ?? $related->created_at)?->toDateString() }}"
                                                  class="block t-data text-[0.6875rem] text-moss/70 dark:text-bone-dim/70 mt-2">
                                                {{ ($related->published_at ?? $related->created_at)?->translatedFormat('d M Y') }}
                                            </time>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @include('website.partials.news-sidebar')
        </div>
    </div>
</section>

@endsection
