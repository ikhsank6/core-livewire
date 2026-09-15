@extends('layouts.website')

@section('title', 'Berita — ' . ($aboutUs->company_name ?? config('app.name')))

@section('content')

@include('website.partials.page-header', [
    'title'      => 'Arsip registrasi dan pengumuman.',
    'breadcrumb' => 'Berita',
    'subtitle'   => 'Setiap catatan membawa nomor registrasi, tanggal terbit, dan instansi yang menerbitkannya.',
])

<section class="py-16 md:py-24">
    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,20rem)] gap-8 lg:gap-12">

            <div>
                {{-- The card titles are h3. Without this the page jumps h1 → h3;
                     the page header already states the section visually. --}}
                <h2 class="sr-only">Daftar catatan</h2>

                @if($news->count() > 0)
                    <div class="mb-4">
                        @include('website.partials.news-card', ['item' => $news->first(), 'featured' => true])
                    </div>

                    @if($news->count() > 1)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($news->skip(1) as $item)
                                @include('website.partials.news-card', ['item' => $item, 'featured' => false])
                            @endforeach
                        </div>
                    @endif
                @else
                    {{-- Empty state: says what happened and offers a way forward. --}}
                    <div class="bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft
                                rounded-2xl px-8 py-20 text-center">
                        <span class="t-data text-3xl text-moss/25 dark:text-bone-dim/25">REG-0000</span>

                        <h2 class="t-card mt-6 text-ink dark:text-bone">Belum ada catatan di kategori ini</h2>

                        <p class="mt-2.5 text-[0.9375rem] text-moss dark:text-bone-dim max-w-sm mx-auto">
                            Coba pilih kategori lain, atau lihat seluruh arsip.
                        </p>

                        <a href="{{ route('news.index') }}"
                           class="mt-7 inline-flex items-center gap-2.5 h-12 px-6 rounded-full
                                  bg-marigold text-ink hover:bg-marigold-dim transition-colors t-micro">
                            Lihat semua berita
                            <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                                 stroke-width="1.8" aria-hidden="true">
                                <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                @endif

                @if($news->hasPages())
                    <div class="mt-10">
                        {{ $news->onEachSide(1)->links('vendor.pagination.website') }}
                    </div>
                @endif
            </div>

            @include('website.partials.news-sidebar')
        </div>
    </div>
</section>

@endsection
