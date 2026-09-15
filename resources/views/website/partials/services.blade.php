@php
    /**
     * Static content — there is no Layanan model. Each card is labelled by its
     * category rather than a sequence number, because the services are not a
     * sequence and numbering them would be decoration.
     */
    $services = [
        [
            'category' => 'Perizinan',
            'title' => 'Izin usaha dan operasional',
            'body' => 'Izin usaha mikro, reklame, dan bangunan. Berkas lengkap terbit dalam tiga hari kerja.',
            'icon' => 'M4.5 6.5h15M4.5 12h15M4.5 17.5h9',
        ],
        [
            'category' => 'Kependudukan',
            'title' => 'Dokumen kependudukan',
            'body' => 'Kartu keluarga, akta kelahiran, akta kematian, dan perekaman KTP elektronik.',
            'icon' => 'M12 12.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM5 19.5a7 7 0 0 1 14 0',
        ],
        [
            'category' => 'Perpajakan',
            'title' => 'Pajak dan retribusi',
            'body' => 'Pendaftaran objek pajak, penetapan, dan pembayaran retribusi daerah secara mandiri.',
            'icon' => 'M12 3v18M8 7.5h6a2.5 2.5 0 0 1 0 5h-4a2.5 2.5 0 0 0 0 5h6',
        ],
        [
            'category' => 'Pengaduan',
            'title' => 'Pengaduan layanan',
            'body' => 'Sampaikan keluhan atas layanan atau fasilitas publik. Setiap laporan memperoleh nomor tindak lanjut.',
            'icon' => 'M20 14.5a2.5 2.5 0 0 1-2.5 2.5H8l-4 3.5v-14A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5Z',
        ],
        [
            'category' => 'Informasi',
            'title' => 'Informasi publik',
            'body' => 'Permohonan informasi publik melalui pejabat pengelola informasi dan dokumentasi.',
            'icon' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 11v5.5M12 7.75h.01',
        ],
        [
            'category' => 'Verifikasi',
            'title' => 'Verifikasi dokumen',
            'body' => 'Periksa keaslian dokumen terbitan portal menggunakan kode verifikasi tanpa datang ke loket.',
            'icon' => 'M9 12.5l2.25 2.25L15.5 10.5M12 3l7 3v5.5c0 4.2-2.9 7.7-7 9.5-4.1-1.8-7-5.3-7-9.5V6Z',
        ],
    ];
@endphp

<section id="layanan" class="py-20 md:py-28 scroll-mt-24">
    <div class="mx-auto max-w-300 px-5 md:px-8">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12">
            <div class="max-w-2xl">
                @include('website.partials.section-heading', [
                    'eyebrow' => 'Layanan',
                    'title' => 'Enam pintu, satu antrean.',
                    'subtitle' => 'Setiap layanan berbagi satu alur verifikasi, satu nomor registrasi, dan satu tempat untuk melacaknya.',
                ])
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($services as $service)
                <article class="aos-init group relative bg-paper dark:bg-ink-card border border-hairline dark:border-pine-soft
                                rounded-2xl p-7 transition-transform duration-200 hover:-translate-y-1">

                    <span class="w-11 h-11 rounded-xl bg-mineral dark:bg-pine/40 grid place-items-center mb-6
                                 group-hover:bg-marigold transition-colors">
                        <svg class="w-5 h-5 text-ink dark:text-bone group-hover:text-ink transition-colors"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $service['icon'] }}"/>
                        </svg>
                    </span>

                    <span class="t-micro text-marigold-deep dark:text-marigold">{{ $service['category'] }}</span>

                    <h3 class="t-card mt-3 text-ink dark:text-bone">{{ $service['title'] }}</h3>

                    <p class="mt-3 text-[0.9375rem] leading-relaxed text-moss dark:text-bone-dim">
                        {{ $service['body'] }}
                    </p>
                </article>
            @endforeach
        </div>
    </div>
</section>
