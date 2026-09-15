@extends('layouts.website')

@section('title', ($aboutUs->company_name ?? config('app.name')) . ' — Portal Layanan Publik Terpadu')

@section('content')

@include('website.partials.hero')
@include('website.partials.register-rail')
@include('website.partials.services')

{{-- ================================================================
     PROFIL
     ================================================================ --}}
@php
    $facts = collect([
        ['label' => 'Alamat',   'value' => $aboutUs?->address],
        ['label' => 'Telepon',  'value' => $aboutUs?->phone],
        ['label' => 'Surel',    'value' => $aboutUs?->email],
        ['label' => 'WhatsApp', 'value' => $aboutUs?->whatsapp],
    ])->filter(fn ($f) => filled($f['value']));
@endphp

<section class="py-20 md:py-28">
    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,22rem)] gap-10 lg:gap-16">

            <div class="aos-init">
                @include('website.partials.section-heading', [
                    'eyebrow' => 'Profil',
                    'title' => 'Satu alur, bukan satu gedung.',
                ])

                @if($aboutUs?->description)
                    <div class="prose">{!! $aboutUs->description !!}</div>
                @endif

                <a href="{{ route('about') }}"
                   class="mt-8 inline-flex items-center gap-2.5 h-12 px-6 rounded-full border
                          border-hairline dark:border-pine-soft text-ink dark:text-bone
                          hover:border-marigold transition-colors t-micro">
                    Profil selengkapnya
                    <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            {{-- Fact list --}}
            @if($facts->isNotEmpty())
                <aside class="aos-init aos-right bg-paper dark:bg-ink-card border border-hairline
                              dark:border-pine-soft rounded-2xl p-7 h-fit lg:sticky lg:top-24">
                    <span class="t-micro text-marigold-deep dark:text-marigold">Kantor layanan</span>

                    <dl class="mt-6 divide-y divide-hairline dark:divide-pine-soft">
                        @foreach($facts as $fact)
                            <div class="py-4 first:pt-0 last:pb-0">
                                <dt class="t-micro text-moss dark:text-bone-dim">{{ $fact['label'] }}</dt>
                                <dd class="mt-1.5 text-[0.9375rem] leading-relaxed text-ink dark:text-bone break-words
                                           {{ in_array($fact['label'], ['Telepon', 'WhatsApp'], true) ? 't-data' : '' }}">
                                    {{ $fact['value'] }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </aside>
            @endif
        </div>
    </div>
</section>

{{-- ================================================================
     BERITA
     ================================================================ --}}
@if(($news ?? collect())->isNotEmpty())
    @php
        $lead = $news->first();
        $rest = $news->slice(1)->take(4);
    @endphp

    <section class="py-20 md:py-28">
        <div class="mx-auto max-w-300 px-5 md:px-8">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
                <div class="max-w-xl">
                    @include('website.partials.section-heading', [
                        'eyebrow' => 'Berita',
                        'title' => 'Yang terbaru dari loket.',
                    ])
                </div>

                <a href="{{ route('news.index') }}"
                   class="shrink-0 mb-12 inline-flex items-center gap-2.5 h-12 px-6 rounded-full border
                          border-hairline dark:border-pine-soft text-ink dark:text-bone
                          hover:border-marigold transition-colors t-micro">
                    Semua berita
                    <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    @include('website.partials.news-card', ['item' => $lead, 'featured' => true])
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($rest as $item)
                        @include('website.partials.news-card', ['item' => $item, 'featured' => false])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

@include('website.partials.testimonials')
@include('website.partials.faq')

{{-- ================================================================
     CTA
     ================================================================ --}}
<section class="pb-20 md:pb-28">
    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-ink dark:bg-ink-card text-bone
                    border border-transparent dark:border-pine-soft px-7 py-14 md:px-16 md:py-20">

            <div aria-hidden="true"
                 class="absolute inset-0 opacity-[0.06]"
                 style="background-image:radial-gradient(circle,#E8A317 1px,transparent 1px);background-size:26px 26px;"></div>

            <div class="relative max-w-3xl">
                <span class="t-micro text-marigold flex items-center gap-2.5">
                    <span class="w-5 h-px bg-marigold"></span>
                    Mulai
                </span>

                <h2 class="t-section mt-6 text-bone">
                    Mulai dari berkas yang <span class="text-marigold">benar</span>.
                </h2>

                <p class="t-lead mt-5 text-bone-dim">
                    Buat akun sekali, lalu ajukan layanan apa pun tanpa mengulang data yang sama.
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2.5 h-13 pl-7 pr-6 rounded-full bg-marigold text-ink
                              hover:bg-marigold-dim transition-colors t-micro">
                        Ajukan layanan
                        <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                             stroke-width="1.8" aria-hidden="true">
                            <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <a href="{{ route('news.index') }}"
                       class="inline-flex items-center h-13 px-7 rounded-full border border-bone/20
                              text-bone hover:bg-bone/10 transition-colors t-micro">
                        Baca panduan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
