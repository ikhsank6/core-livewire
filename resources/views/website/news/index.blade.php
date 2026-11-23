@extends('layouts.website')

@section('title', 'Berita | ' . ($aboutUs->company_name ?? config('app.name')))

@section('content')

@include('website.partials.page-header', [
    'title'      => 'Berita dan pengumuman.',
    'breadcrumb' => 'Berita',
    'subtitle'   => 'Kabar terbaru seputar layanan, jadwal, dan pengumuman resmi dari instansi terkait.',
])

<section class="py-14 md:py-20">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,18rem)] lg:gap-16">

        <div>
            {{-- Card titles are h3; this keeps the outline h1, h2, h3. --}}
            <h2 class="sr-only">Daftar berita</h2>

            @if($news->count() > 0)
                @include('website.partials.news-card', ['item' => $news->first(), 'variant' => 'lead'])

                @if($news->count() > 1)
                    <div class="mt-14 grid gap-x-8 gap-y-12 border-t border-line pt-12 sm:grid-cols-2">
                        @foreach($news->skip(1) as $item)
                            @include('website.partials.news-card', ['item' => $item, 'variant' => 'card'])
                        @endforeach
                    </div>
                @endif
            @else
                {{-- Empty state: says what happened and offers the way forward. --}}
                <div class="border-y border-line px-2 py-20">
                    <p class="subtitle">Belum ada berita di kategori ini.</p>
                    <p class="mt-3 max-w-[44ch]">Pilih kategori lain, atau buka seluruh berita.</p>
                    <a href="{{ route('news.index') }}" class="btn btn-solid mt-7">Lihat semua berita</a>
                </div>
            @endif

            @if($news->hasPages())
                <div class="mt-14">
                    {{ $news->onEachSide(1)->links('vendor.pagination.website') }}
                </div>
            @endif
        </div>

        @include('website.partials.news-sidebar')
    </div>
</section>

@endsection
