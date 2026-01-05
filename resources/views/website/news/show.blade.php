@extends('layouts.website')

@section('title', $news->title . ' - ' . ($aboutUs->company_name ?? config('app.name')))
@section('description', $news->excerpt ?? Str::limit(strip_tags($news->content), 160))

@section('content')
    <!-- Page Header -->
    <section class="relative h-[250px] md:h-[300px] bg-dark flex items-center justify-center"
        style="background-image: linear-gradient(rgba(26,26,46,0.9), rgba(26,26,46,0.9)), url('{{ $news->image ? Storage::url($news->image) : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1920' }}'); background-size: cover; background-position: center;">
        <div class="text-center text-white px-4">
            <div class="flex items-center justify-center gap-2 text-sm mb-4 flex-wrap">
                <a href="/" class="hover:text-primary">Home</a>
                <span>:</span>
                <a href="{{ route('news.index') }}" class="hover:text-primary">News</a>
                <span>:</span>
                <span class="text-primary">{{ Str::limit($news->title, 30) }}</span>
            </div>
            <h1 class="text-2xl md:text-4xl font-bold max-w-3xl mx-auto">{{ $news->title }}</h1>
        </div>
    </section>

    <!-- News Content -->
    <section class="py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-xl overflow-hidden shadow-sm">
                        @if($news->image)
                            <div class="relative">
                                <img src="{{ Storage::url($news->image) }}" alt="{{ $news->title }}"
                                    class="w-full h-[300px] md:h-[400px] object-cover">
                                <div class="absolute top-4 left-4 bg-primary text-white text-center px-3 py-2 rounded">
                                    <span class="font-bold text-xl block">{{ $news->published_at?->format('d') }}</span>
                                    <span class="text-xs">{{ $news->published_at?->format('M') }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="p-6 md:p-8">
                            <div class="flex items-center gap-4 text-sm text-gray-500 mb-4 flex-wrap">
                                @if($news->category)
                                    <span
                                        class="bg-primary/10 text-primary px-3 py-1 rounded font-semibold">{{ $news->category->name }}</span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $news->published_at?->format('d M Y') }}
                                </span>
                            </div>

                            <h1 class="text-2xl md:text-3xl font-bold text-dark mb-6">{{ $news->title }}</h1>

                            <div class="prose text-gray-700">
                                {!! $news->content !!}
                            </div>

                            <!-- Share -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="flex items-center gap-4 flex-wrap">
                                    <span class="font-semibold text-dark">Share:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                        target="_blank"
                                        class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:opacity-80">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                        </svg>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}"
                                        target="_blank"
                                        class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center hover:opacity-80">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->url()) }}"
                                        target="_blank"
                                        class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:opacity-80">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Related News -->
                    @if($relatedNews->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-xl font-bold text-dark mb-6">Related News</h3>
                            <div class="grid sm:grid-cols-2 gap-6">
                                @foreach($relatedNews as $related)
                                    <article
                                        class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all group">
                                        <a href="{{ route('news.show', $related->slug) }}" class="flex gap-4 p-4">
                                            <div class="w-24 h-24 rounded overflow-hidden shrink-0">
                                                @if($related->image)
                                                    <img src="{{ Storage::url($related->image) }}" alt="{{ $related->title }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gray-200"></div>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-dark group-hover:text-primary line-clamp-2">
                                                    {{ $related->title }}</h4>
                                                <span
                                                    class="text-xs text-gray-400 mt-1 block">{{ $related->published_at?->format('d M Y') }}</span>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                @include('website.partials.news-sidebar')
            </div>
        </div>
    </section>
@endsection