@extends('layouts.website')

@section('title', 'Tentang Kami - ' . ($aboutUs->company_name ?? config('app.name')))

@section('content')

{{-- Page Header --}}
@include('website.partials.page-header', [
    'title'      => 'Tentang Kami',
    'breadcrumb' => 'Tentang Kami',
    'subtitle'   => 'Kenali lebih jauh siapa kami dan apa yang kami perjuangkan.',
])


{{-- ================================================================
     ABOUT CONTENT — 2-kolom
     ================================================================ --}}
<section class="py-20 md:py-28 bg-white dark:bg-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-14 items-start">

            {{-- Visual --}}
            <div class="aos-init aos-left">
                @if($aboutUs?->logo)
                <div class="relative">
                    <div class="bg-section-gray dark:bg-dark-card border border-slate-100 dark:border-dark-border rounded-2xl p-12 flex items-center justify-center shadow-lg">
                        <img src="{{ Storage::url($aboutUs->logo) }}"
                             alt="{{ $aboutUs->company_name }}"
                             class="max-w-full max-h-72 object-contain dark:brightness-0 dark:invert">
                    </div>
                    {{-- Decorative elements --}}
                    <div class="absolute -z-10 -bottom-6 -right-6 w-40 h-40 rounded-2xl"
                         style="background: rgba(58,108,244,0.08);"></div>
                    <div class="absolute -z-10 -top-4 -left-4 w-24 h-24 rounded-xl"
                         style="background: rgba(249,115,22,0.07);"></div>
                </div>
                @else
                <div class="relative w-full h-[400px] bg-gradient-to-br from-primary/8 to-accent/6 dark:from-primary/5 dark:to-accent/5 rounded-2xl border border-slate-100 dark:border-dark-border shadow-lg flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-primary/30">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/>
                            </svg>
                        </div>
                        <p class="font-heading font-bold text-2xl text-slate-700 dark:text-white">
                            {{ $aboutUs->company_name ?? config('app.name') }}
                        </p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="aos-init aos-right">
                @include('website.partials.section-heading', [
                    'eyebrow' => 'Tentang Perusahaan',
                    'title'   => $aboutUs->company_name ?? 'Tentang Kami',
                    'align'   => 'left',
                ])

                <div class="prose dark:prose-invert max-w-none mb-8">
                    {!! $aboutUs->description ?? '<p>Selamat datang. Kami berkomitmen memberikan layanan terbaik bagi klien kami.</p>' !!}
                </div>

                <a href="{{ route('about') }}#contact" class="btn-primary-solid">
                    Hubungi Kami
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ================================================================
     CONTACT & MAP
     ================================================================ --}}
<section id="contact" class="py-20 md:py-28 bg-section-gray dark:bg-dark-card">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @include('website.partials.section-heading', [
            'eyebrow' => 'Kontak',
            'title'   => 'Hubungi <span style="color: #3a6cf4;">Kami</span>',
            'subtitle' => 'Ada pertanyaan atau ingin berkolaborasi? Jangan ragu untuk menghubungi kami.',
        ])

        <div class="grid lg:grid-cols-2 gap-8">

            {{-- Contact card --}}
            <div class="card-base p-7 md:p-8 aos-init aos-left">
                <h3 class="font-heading font-bold text-slate-900 dark:text-white text-xl mb-7">
                    Informasi Kontak
                </h3>

                <div class="space-y-4">
                    @if($aboutUs?->phone)
                    <a href="tel:{{ $aboutUs->phone }}"
                       class="flex items-center gap-4 p-4 rounded-xl hover:bg-section-gray dark:hover:bg-dark transition-colors group">
                        <div class="w-12 h-12 bg-primary/8 dark:bg-primary/12 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-primary/15 transition-colors">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-0.5 font-medium">Telepon</p>
                            <p class="font-semibold text-slate-900 dark:text-white group-hover:text-primary transition-colors">{{ $aboutUs->phone }}</p>
                        </div>
                    </a>
                    @endif

                    @if($aboutUs?->email)
                    <a href="mailto:{{ $aboutUs->email }}"
                       class="flex items-center gap-4 p-4 rounded-xl hover:bg-section-gray dark:hover:bg-dark transition-colors group">
                        <div class="w-12 h-12 bg-primary/8 dark:bg-primary/12 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-primary/15 transition-colors">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-0.5 font-medium">Email</p>
                            <p class="font-semibold text-slate-900 dark:text-white group-hover:text-primary transition-colors">{{ $aboutUs->email }}</p>
                        </div>
                    </a>
                    @endif

                    @if($aboutUs?->address)
                    @if($aboutUs?->map_url)
                    <a href="{{ $aboutUs->map_url }}" target="_blank"
                       class="flex items-start gap-4 p-4 rounded-xl cursor-pointer hover:bg-section-gray dark:hover:bg-dark transition-colors group">
                    @else
                    <div class="flex items-start gap-4 p-4 rounded-xl transition-colors group">
                    @endif
                        <div class="w-12 h-12 bg-primary/8 dark:bg-primary/12 rounded-xl flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-primary/15 transition-colors">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-0.5 font-medium">Alamat</p>
                            <p class="font-semibold text-slate-900 dark:text-white group-hover:text-primary transition-colors leading-relaxed">{{ $aboutUs->address }}</p>
                        </div>
                    @if($aboutUs?->map_url)
                    </a>
                    @else
                    </div>
                    @endif
                    @endif

                    @if($aboutUs?->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $aboutUs->whatsapp) }}" target="_blank"
                       class="flex items-center gap-4 p-4 rounded-xl hover:bg-green-50 dark:hover:bg-green-900/10 transition-colors group">
                        <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-green-500/20 transition-colors">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-0.5 font-medium">WhatsApp</p>
                            <p class="font-semibold text-slate-900 dark:text-white group-hover:text-green-500 transition-colors">Chat dengan kami</p>
                        </div>
                    </a>
                    @endif
                </div>

                {{-- Social links --}}
                <div class="mt-7 pt-7 border-t border-slate-100 dark:border-dark-border">
                    @include('website.partials.social-links')
                </div>
            </div>

            {{-- Map --}}
            <div class="rounded-2xl overflow-hidden border border-slate-100 dark:border-dark-border min-h-[420px] bg-section-gray dark:bg-dark-card shadow-lg aos-init aos-right">
                @php
                    $mapLat = $aboutUs?->latitude;
                    $mapLng = $aboutUs?->longitude;
                    if (!$mapLat && $aboutUs?->map_url && preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $aboutUs->map_url, $m)) {
                        $mapLat = $m[1]; $mapLng = $m[2];
                    }
                @endphp

                @if($mapLat && $mapLng)
                    <iframe
                        src="https://www.google.com/maps?q={{ $mapLat }},{{ $mapLng }}&z=15&output=embed"
                        width="100%" height="100%"
                        style="border:0; min-height:420px;"
                        allowfullscreen loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi {{ $aboutUs->company_name ?? 'Perusahaan' }}"
                        class="w-full h-full">
                    </iframe>
                @else
                    <div class="w-full min-h-[420px] flex items-center justify-center text-slate-400 dark:text-slate-500">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary/8 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Lokasi belum tersedia</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Tambahkan koordinat di pengaturan</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
