@extends('layouts.website')

@section('title', 'Berita - ' . ($aboutUs->company_name ?? config('app.name')))

@section('content')

{{-- Page Header --}}
@include('website.partials.page-header', [
    'title'      => 'Berita Terbaru',
    'breadcrumb' => 'Berita',
])

{{-- News Content --}}
<section class="py-16 md:py-24 bg-slate-50 dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid lg:grid-cols-3 gap-10">

            {{-- News Grid --}}
            <div class="lg:col-span-2">
                @if($news->count() > 0)

                    {{-- Featured: first article --}}
                    <div class="mb-8">
                        @include('website.partials.news-card', ['item' => $news->first(), 'featured' => true])
                    </div>

                    {{-- Remaining articles --}}
                    @if($news->count() > 1)
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach($news->skip(1) as $item)
                            @include('website.partials.news-card', ['item' => $item])
                        @endforeach
                    </div>
                    @endif

                @else
                    <div class="text-center py-20 bg-white dark:bg-dark-card rounded-2xl border border-slate-100 dark:border-dark-border">
                        <div class="w-16 h-16 mx-auto mb-5 bg-slate-100 dark:bg-dark rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Belum Ada Artikel</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Kunjungi kembali untuk mendapatkan berita terbaru.</p>
                    </div>
                @endif

                {{-- Pagination --}}
                @if($news->hasPages())
                    <div class="mt-10">
                        {{ $news->links() }}
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            @include('website.partials.news-sidebar')

        </div>
    </div>
</section>

@endsection
