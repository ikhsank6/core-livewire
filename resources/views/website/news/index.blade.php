@extends('layouts.website')

@section('title', 'News - ' . ($aboutUs->company_name ?? config('app.name')))

@section('content')
    {{-- Page Header --}}
    @include('website.partials.page-header', ['title' => 'Latest News', 'breadcrumb' => 'News'])

    {{-- News Content --}}
    <section class="py-12 md:py-20 bg-slate-50 dark:bg-dark">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- News Grid --}}
                <div class="lg:col-span-2">
                    <div class="grid sm:grid-cols-2 gap-6">
                        @forelse($news as $item)
                            <article class="bg-white dark:bg-dark-card rounded-xl overflow-hidden shadow-sm dark:shadow-none border border-transparent dark:border-dark-border hover:shadow-lg dark:hover:shadow-2xl dark:hover:shadow-primary/5 transition-all group">
                                <div class="relative h-48 overflow-hidden">
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-slate-200 dark:bg-dark flex items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 left-4 bg-primary text-white text-xs px-3 py-1 rounded-full text-center">
                                        <span class="font-bold block">{{ $item->published_at?->format('d') }}</span>
                                        <span class="text-[10px]">{{ $item->published_at?->format('M') }}</span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    @if($item->category)
                                        <span class="text-primary text-xs font-semibold">{{ $item->category->name }}</span>
                                    @endif
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg mt-1 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                        <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                                    </h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2">
                                        {{ Str::limit($item->excerpt ?? strip_tags($item->content), 100) }}</p>
                                    <a href="{{ route('news.show', $item->slug) }}"
                                        class="text-primary font-semibold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                                        Read More
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-2 text-center py-12 bg-white dark:bg-dark-card rounded-xl border border-transparent dark:border-dark-border">
                                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                <p class="text-slate-500 dark:text-slate-400">No news available.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $news->links() }}
                    </div>
                </div>

                {{-- Sidebar --}}
                @include('website.partials.news-sidebar')
            </div>
        </div>
    </section>
@endsection