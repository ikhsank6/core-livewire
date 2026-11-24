@extends('layouts.website')

@section('title', 'Profil | ' . ($aboutUs->company_name ?? config('app.name')))

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
        ],
        [
            'label' => 'WhatsApp',
            'value' => $aboutUs?->whatsapp,
            'href' => $aboutUs?->whatsapp ? 'https://wa.me/'.preg_replace('/\D+/', '', $aboutUs->whatsapp) : null,
        ],
        [
            'label' => 'Surel',
            'value' => $aboutUs?->email,
            'href' => $aboutUs?->email ? 'mailto:'.$aboutUs->email : null,
        ],
        [
            'label' => 'Alamat',
            'value' => $aboutUs?->address,
            'href' => $aboutUs?->map_url,
        ],
    ])->filter(fn ($c) => filled($c['value']));
@endphp

@section('content')

@include('website.partials.page-header', [
    'title'      => 'Lembaga yang menguji kompetensi, bukan lamanya Anda berlatih.',
    'breadcrumb' => 'Profil',
    'subtitle'   => 'Siapa yang mengelola uji kompetensi ini, apa yang dikerjakan, dan ke mana menghubunginya.',
])

{{-- ================================================================
     PROFIL
     ================================================================ --}}
<section class="py-16 md:py-24">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,19rem)] lg:gap-20">

        <div>
            <h2 class="title">{{ $aboutUs->company_name ?? 'Tentang kami' }}</h2>

            <div class="prose mt-8">
                {!! $aboutUs->description ?? '<p>Profil belum diisi.</p>' !!}
            </div>
        </div>

        {{-- Identity --}}
        <aside class="self-start lg:sticky lg:top-24">
            <div class="panel flex flex-col items-start p-8">
                @if($aboutUs?->logo)
                    <img src="{{ Storage::url($aboutUs->logo) }}"
                         alt="{{ $aboutUs->company_name }}"
                         class="max-w-full max-h-32 object-contain">
                @else
                    <span class="grid h-20 w-20 place-items-center rounded-full bg-rimba" aria-hidden="true">
                        <svg class="h-12 w-12" viewBox="0 0 36 36" fill="none">
                            <circle cx="18" cy="18" r="16" stroke="#E4472D" stroke-width="2.4" stroke-linecap="round" stroke-dasharray="0.1 3.6"/>
                            <circle cx="18" cy="18" r="12.5" fill="#E4472D"/>
                            <path d="M11.8 18.4l4.4 4.4 8.2-9.2" stroke="#1B0B07" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                @endif

                <p class="subtitle mt-6">{{ $aboutUs->company_name ?? config('app.name') }}</p>
                <p class="mt-1.5 text-[0.9375rem] text-muted">Lembaga Sertifikasi Profesi</p>
            </div>
        </aside>
    </div>
</section>

{{-- ================================================================
     KONTAK & PETA
     ================================================================ --}}
<section id="kontak" class="scroll-mt-20 border-t border-line py-16 md:py-24">
    <div class="wrap">

        <div class="max-w-2xl">
            <h2 class="title">Kanal resmi.</h2>
            <p class="lead mt-5">Hubungi kami pada hari kerja. Permohonan sertifikasi lewat portal tetap diterima kapan saja.</p>
        </div>

        <div class="mt-12 grid gap-10 lg:grid-cols-2 lg:gap-16">

            <div>
                <dl class="border-t border-line">
                    @foreach($channels as $channel)
                        <div class="grid gap-1 border-b border-line py-5 sm:grid-cols-[8rem_minmax(0,1fr)] sm:gap-6">
                            <dt class="text-sm text-muted sm:pt-0.5">{{ $channel['label'] }}</dt>
                            <dd class="text-[0.9375rem] font-medium leading-relaxed wrap-break-word">
                                @if($channel['href'])
                                    <a href="{{ $channel['href'] }}"
                                       @if(Str::startsWith($channel['href'], 'http')) target="_blank" rel="noopener noreferrer" @endif
                                       class="inline-flex min-h-11 items-center text-ink underline decoration-line underline-offset-4 hover:decoration-cap transition-colors">
                                        {{ $channel['value'] }}
                                    </a>
                                @else
                                    <span class="text-ink">{{ $channel['value'] }}</span>
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>

                @if($aboutUs?->facebook || $aboutUs?->instagram || $aboutUs?->twitter || $aboutUs?->youtube || $aboutUs?->linkedin)
                    <div class="mt-8">
                        <p class="text-sm text-muted mb-1">Kanal sosial</p>
                        @include('website.partials.social-links')
                    </div>
                @endif
            </div>

            {{-- Map --}}
            <div class="panel overflow-hidden min-h-96">
                @if($mapLat && $mapLng)
                    <iframe src="https://www.google.com/maps?q={{ $mapLat }},{{ $mapLng }}&z=15&output=embed"
                            title="Peta lokasi {{ $aboutUs->company_name ?? 'kantor layanan' }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                            class="h-full min-h-96 w-full border-0"></iframe>
                @else
                    <div class="grid h-full min-h-96 place-items-center p-8 text-center">
                        <div>
                            <p class="subtitle">Lokasi belum diatur</p>
                            <p class="mt-2 max-w-[32ch] text-[0.9375rem]">
                                Tambahkan koordinat di pengaturan profil untuk menampilkan peta.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
