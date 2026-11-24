@extends('layouts.website')

@section('title', $news->title . ' | ' . ($aboutUs->company_name ?? config('app.name')))
@section('description', $news->excerpt ?? Str::limit(strip_tags($news->content), 160))

@php
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

<section class="py-14 md:py-20">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,18rem)] lg:gap-16">

        <div>
            <article>
                @if($news->image)
                    <img src="{{ Storage::url($news->image) }}"
                         alt="{{ $news->title }}"
                         class="aspect-video w-full rounded-2xl object-cover">
                @endif

                <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-muted">
                    @if($news->category)
                        <span class="font-semibold text-accent-text">{{ $news->category->name }}</span>
                    @endif
                    <time datetime="{{ $date?->toDateString() }}">{{ $date?->translatedFormat('d M Y') }}</time>
                </div>

                @if($news->excerpt)
                    <p class="mt-6 max-w-[56ch] text-[1.375rem] font-semibold leading-snug tracking-tight text-ink">{{ $news->excerpt }}</p>
                @endif

                <div class="prose mt-8">{!! $news->content !!}</div>

                {{-- Share --}}
                <div class="mt-12 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-line pt-6">
                    <span class="text-sm font-semibold text-ink">Bagikan</span>
                    <ul class="-ml-2 flex items-center">
                        @foreach($shares as $share)
                            <li>
                                <a href="{{ $share['url'] }}" target="_blank" rel="noopener noreferrer"
                                   aria-label="{{ $share['label'] }}"
                                   class="grid h-11 w-11 place-items-center rounded-md text-muted transition-colors hover:text-ink">
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="{{ $share['path'] }}"/>
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </article>

            {{-- Related --}}
            @if($relatedNews->count() > 0)
                <div class="mt-16 border-t border-line pt-12">
                    <h2 class="subtitle">Berita terkait</h2>

                    <div class="mt-8 grid gap-8 sm:grid-cols-2">
                        @foreach($relatedNews as $related)
                            @include('website.partials.news-card', ['item' => $related, 'variant' => 'row'])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @include('website.partials.news-sidebar')
    </div>
</section>

@endsection
