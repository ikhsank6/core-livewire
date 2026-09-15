@extends('layouts.website')

@section('title', 'Profil — ' . ($aboutUs->company_name ?? config('app.name')))

@php
    $mapLat = $aboutUs?->latitude;
    $mapLng = $aboutUs?->longitude;

    if (! $mapLat && $aboutUs?->map_url && preg_match('/(-?\d+\.\d+),(-?\d+\.\d+)/', $aboutUs->map_url, $m)) {
        $mapLat = $m[1];
        $mapLng = $m[2];
    }

    $channels = collect([
        [
            'label' => 'Telepon',
            'value' => $aboutUs?->phone,
            'href' => $aboutUs?->phone ? 'tel:'.preg_replace('/\D+/', '', $aboutUs->phone) : null,
            'mono' => true,
            'icon' => 'M5 4h3l1.5 4-2 1.5a12 12 0 0 0 5.5 5.5L14.5 13l4 1.5V18a2 2 0 0 1-2.2 2A15.5 15.5 0 0 1 3.5 6.2 2 2 0 0 1 5 4Z',
        ],
        [
            'label' => 'WhatsApp',
            'value' => $aboutUs?->whatsapp,
            'href' => $aboutUs?->whatsapp ? 'https://wa.me/'.preg_replace('/\D+/', '', $aboutUs->whatsapp) : null,
            'mono' => true,
            'icon' => 'M20 11.5a7.5 7.5 0 0 1-11 6.7L4 19.5l1.4-4.8A7.5 7.5 0 1 1 20 11.5Z',
        ],
        [
            'label' => 'Surel',
            'value' => $aboutUs?->email,
            'href' => $aboutUs?->email ? 'mailto:'.$aboutUs->email : null,
            'mono' => false,
            'icon' => 'M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm.5 0 8.5 6 8.5-6',
        ],
        [
            'label' => 'Alamat',
            'value' => $aboutUs?->address,
            'href' => $aboutUs?->map_url,
            'mono' => false,
            'icon' => 'M12 21s7-5.2 7-10.5a7 7 0 1 0-14 0C5 15.8 12 21 12 21Zm0-8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z',
        ],
    ])->filter(fn ($c) => filled($c['value']));
@endphp

@section('content')

@include('website.partials.page-header', [
    'title'      => 'Satu alur, bukan satu gedung.',
    'breadcrumb' => 'Profil',
    'subtitle'   => 'Siapa yang mengelola portal ini, apa yang dikerjakan, dan ke mana menghubunginya.',
])

{{-- ================================================================
     PROFIL
     ================================================================ --}}
<section class="py-20 md:py-28">
    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,20rem)] gap-10 lg:gap-16">

            <div class="aos-init">
                @include('website.partials.section-heading', [
                    'eyebrow' => 'Tentang',
                    'title' => $aboutUs->company_name ?? 'Tentang kami',
                ])

                <div class="prose">
                    {!! $aboutUs->description ?? '<p>Profil belum diisi.</p>' !!}
                </div>

                <a href="#kontak"
                   class="mt-8 inline-flex items-center gap-2.5 h-12 px-6 rounded-full bg-marigold text-ink
                          hover:bg-marigold-dim transition-colors t-micro">
                    Hubungi kami
                    <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            {{-- Identity card --}}
            <aside class="aos-init aos-right h-fit lg:sticky lg:top-24">
                <div class="bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft
                            rounded-2xl p-8 flex flex-col items-center text-center">
                    @if($aboutUs?->logo)
                        <img src="{{ Storage::url($aboutUs->logo) }}"
                             alt="{{ $aboutUs->company_name }}"
                             class="max-w-full max-h-40 object-contain">
                    @else
                        <span class="w-20 h-20 rounded-2xl bg-ink grid place-items-center">
                            <svg class="w-10 h-10 text-bone" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M4 17V7.2a2 2 0 0 1 .9-1.66l4-2.67a2 2 0 0 1 2.2 0l4 2.67A2 2 0 0 1 16 7.2V17"
                                      stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                <path d="M2.5 17h15" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                <circle cx="12.3" cy="11.5" r="1.15" fill="#E8A317"/>
                            </svg>
                        </span>
                    @endif

                    <h2 class="t-card mt-6 text-ink dark:text-bone">
                        {{ $aboutUs->company_name ?? config('app.name') }}
                    </h2>

                    <p class="t-micro mt-3 text-moss dark:text-bone-dim">
                        Pelayanan Terpadu Satu Pintu
                    </p>
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- ================================================================
     KONTAK & PETA
     ================================================================ --}}
<section id="kontak" class="pb-20 md:pb-28 scroll-mt-24">
    <div class="mx-auto max-w-300 px-5 md:px-8">

        @include('website.partials.section-heading', [
            'eyebrow' => 'Kontak',
            'title' => 'Kanal resmi.',
            'subtitle' => 'Gunakan salah satu kanal berikut pada hari kerja. Pengajuan melalui portal tetap diterima setiap saat.',
        ])

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Channels --}}
            <div class="bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft rounded-2xl p-7">
                <dl class="divide-y divide-hairline dark:divide-pine-soft">
                    @foreach($channels as $channel)
                        <div class="flex gap-4 py-5 first:pt-0 last:pb-0">
                            <span class="w-10 h-10 shrink-0 rounded-xl bg-mineral dark:bg-pine/40 grid place-items-center">
                                <svg class="w-4.5 h-4.5 text-marigold-deep dark:text-marigold" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                                     stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $channel['icon'] }}"/>
                                </svg>
                            </span>

                            <div class="min-w-0">
                                <dt class="t-micro text-moss dark:text-bone-dim">{{ $channel['label'] }}</dt>
                                <dd class="mt-1.5 text-[0.9375rem] leading-relaxed wrap-break-word
                                           {{ $channel['mono'] ? 't-data' : '' }}">
                                    @if($channel['href'])
                                        <a href="{{ $channel['href'] }}"
                                           @if(Str::startsWith($channel['href'], 'http')) target="_blank" rel="noopener noreferrer" @endif
                                           class="text-ink dark:text-bone hover:text-marigold-deep dark:hover:text-marigold transition-colors">
                                            {{ $channel['value'] }}
                                        </a>
                                    @else
                                        <span class="text-ink dark:text-bone">{{ $channel['value'] }}</span>
                                    @endif
                                </dd>
                            </div>
                        </div>
                    @endforeach
                </dl>

                <div class="mt-7 pt-6 border-t border-hairline dark:border-pine-soft">
                    <span class="t-micro text-moss dark:text-bone-dim block mb-3.5">Kanal sosial</span>
                    @include('website.partials.social-links')
                </div>
            </div>

            {{-- Map --}}
            <div class="rounded-2xl overflow-hidden border border-hairline dark:border-pine-soft
                        bg-paper dark:bg-ink-card min-h-104">
                @if($mapLat && $mapLng)
                    <iframe src="https://www.google.com/maps?q={{ $mapLat }},{{ $mapLng }}&z=15&output=embed"
                            title="Peta lokasi {{ $aboutUs->company_name ?? 'kantor layanan' }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                            class="w-full h-full min-h-104 border-0"></iframe>
                @else
                    <div class="w-full h-full min-h-104 grid place-items-center p-8 text-center">
                        <div>
                            <span class="w-14 h-14 rounded-2xl bg-mineral dark:bg-pine/40 grid place-items-center mx-auto">
                                <svg class="w-6 h-6 text-moss dark:text-bone-dim" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <path d="M12 21s7-5.2 7-10.5a7 7 0 1 0-14 0C5 15.8 12 21 12 21Z" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10.5" r="2.5"/>
                                </svg>
                            </span>
                            <p class="t-card mt-5 text-ink dark:text-bone">Lokasi belum diatur</p>
                            <p class="mt-2 text-[0.9375rem] text-moss dark:text-bone-dim">
                                Tambahkan koordinat pada pengaturan profil untuk menampilkan peta.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
