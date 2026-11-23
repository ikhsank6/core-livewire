@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' | Portal Layanan Publik Terpadu')

@section('content')

@include('website.partials.hero')
@include('website.partials.services')
@include('website.partials.journey')

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
                <h2 class="title">Kabar terbaru dari loket.</h2>
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

{{-- ================================================================
     PROFIL: the organisation's own words, set large
     ================================================================ --}}
@php
    $statement = filled($aboutUs?->description)
        ? Str::words(trim(preg_replace('/\s+/', ' ', strip_tags($aboutUs->description))), 46)
        : null;

    $facts = collect([
        ['label' => 'Alamat',   'value' => $aboutUs?->address],
        ['label' => 'Telepon',  'value' => $aboutUs?->phone],
        ['label' => 'Surel',    'value' => $aboutUs?->email],
        ['label' => 'WhatsApp', 'value' => $aboutUs?->whatsapp],
    ])->filter(fn ($f) => filled($f['value']));
@endphp

@if($statement || $facts->isNotEmpty())
    <section class="border-t border-line py-20 md:py-28">
        <div class="wrap grid gap-14 lg:grid-cols-[minmax(0,1fr)_minmax(0,22rem)] lg:gap-20">

            <div>
                @if($statement)
                    <p class="font-display text-[clamp(1.5rem,2.8vw,2.375rem)] font-semibold leading-[1.25] tracking-tight text-ink max-w-[30ch]"
                       style="font-variation-settings: 'wdth' 92;">
                        {{ $statement }}
                    </p>
                @endif

                <a href="{{ route('about') }}" class="btn btn-solid btn-lg mt-10">Baca profil lengkap</a>
            </div>

            @if($facts->isNotEmpty())
                <dl class="self-start border-t border-line">
                    @foreach($facts as $fact)
                        <div class="border-b border-line py-5">
                            <dt class="text-sm text-muted">{{ $fact['label'] }}</dt>
                            <dd class="mt-1 text-[0.9375rem] font-medium text-ink wrap-break-word">{{ $fact['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif
        </div>
    </section>
@endif

@include('website.partials.faq')
@include('website.partials.cta')

@endsection
