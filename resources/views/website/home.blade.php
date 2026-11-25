@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' | Lembaga Sertifikasi Profesi')

@section('content')

@include('website.partials.hero')
@include('website.partials.skema')
@include('website.partials.alur')
@include('website.partials.jadwal')
@include('website.partials.sorotan')

{{-- ================================================================
     BERITA: one lead story with two shorter ones beside it
     ================================================================ --}}
@if(($news ?? collect())->isNotEmpty())
    @php
        $lead = $news->first();
        $rest = $news->slice(1)->take(2);
    @endphp

    <section class="py-20 md:py-28">
        <div class="wrap">
            <div class="flex flex-wrap items-end justify-between gap-x-8 gap-y-4">
                <h2 class="title">Kabar terbaru dari LSP.</h2>
                <a href="{{ route('news.index') }}" class="btn btn-line">Semua berita</a>
            </div>

            <div class="mt-12 grid gap-x-12 gap-y-12 lg:grid-cols-[minmax(0,1.35fr)_minmax(0,1fr)]">
                @include('website.partials.news-card', ['item' => $lead, 'variant' => 'lead'])

                @if($rest->isNotEmpty())
                    <div class="flex flex-col gap-8 lg:border-l lg:border-line lg:pl-12">
                        @foreach($rest as $item)
                            @include('website.partials.news-card', ['item' => $item, 'variant' => 'row'])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif

{{-- FAQ and the closing band share one lattice background, so no divider line is needed between them --}}
<div class="relative isolate overflow-hidden">
    @include('website.partials.lattice', ['class' => 'pointer-events-none absolute inset-0 -z-10 h-full w-full text-brand opacity-[0.1] dark:text-sky dark:opacity-[0.09]'])
    @include('website.partials.faq')
    @include('website.partials.cta')
</div>

@endsection
