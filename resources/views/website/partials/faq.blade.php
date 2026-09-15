@php
    // Static content — no FAQ model.
    $faqs = [
        [
            'q' => 'Apakah harus punya akun untuk mengajukan layanan?',
            'a' => 'Ya. Akun diperlukan agar setiap pengajuan terhubung dengan identitas pemohon dan '
                .'statusnya dapat dilacak. Pendaftaran cukup sekali menggunakan nomor induk kependudukan '
                .'dan alamat surel aktif.',
        ],
        [
            'q' => 'Berapa lama berkas saya diproses?',
            'a' => 'Bergantung jenis layanan. Izin usaha mikro terbit dalam tiga hari kerja apabila berkas '
                .'lengkap sejak awal. Estimasi waktu untuk setiap layanan ditampilkan sebelum Anda '
                .'mengirimkan pengajuan.',
        ],
        [
            'q' => 'Bagaimana jika dokumen saya kurang?',
            'a' => 'Pemberitahuan dikirim melalui pesan singkat dan surel pada hari yang sama, disertai '
                .'rincian dokumen yang perlu dilengkapi. Perbaikan dapat diunggah langsung tanpa '
                .'mengulang pengajuan dari awal.',
        ],
        [
            'q' => 'Apakah dokumen elektronik sah tanpa legalisasi?',
            'a' => 'Sah. Setiap dokumen terbitan portal memuat kode verifikasi yang dapat diperiksa oleh '
                .'siapa pun melalui halaman verifikasi. Legalisasi salinan fisik tidak lagi diperlukan.',
        ],
        [
            'q' => 'Saya tidak bisa datang pada jam kerja. Ada pilihan lain?',
            'a' => 'Pengajuan melalui portal dapat dilakukan dua puluh empat jam. Untuk layanan yang '
                .'memerlukan kehadiran, unit layanan bergerak menyambangi dua belas kelurahan dengan '
                .'jadwal tetap setiap pekan.',
        ],
    ];
@endphp

<section class="py-20 md:py-28">
    <div class="mx-auto max-w-300 px-5 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,22rem)_minmax(0,1fr)] gap-10 lg:gap-16">

            <div>
                @include('website.partials.section-heading', [
                    'eyebrow' => 'Pertanyaan',
                    'title' => 'Sering ditanyakan.',
                    'subtitle' => 'Belum terjawab? Hubungi kami melalui kanal yang tercantum di bawah.',
                ])

                <a href="{{ route('about') }}"
                   class="inline-flex items-center gap-2.5 h-12 px-6 rounded-full border border-hairline
                          dark:border-pine-soft text-ink dark:text-bone hover:border-marigold
                          transition-colors t-micro">
                    Kanal kontak
                    <svg class="w-3.5 h-3.5" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                         stroke-width="1.8" aria-hidden="true">
                        <path d="M3 8h10M9 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <div class="divide-y divide-hairline dark:divide-pine-soft border-t border-b
                        border-hairline dark:border-pine-soft">
                @foreach($faqs as $index => $faq)
                    <div x-data="{ open: false }">
                        <h3>
                            <button type="button"
                                    @click="open = !open"
                                    :aria-expanded="open ? 'true' : 'false'"
                                    aria-controls="faq-panel-{{ $index }}"
                                    class="w-full flex items-start justify-between gap-6 py-6 text-left
                                           group cursor-pointer">
                                <span class="t-card text-ink dark:text-bone group-hover:text-marigold-deep dark:group-hover:text-marigold transition-colors">
                                    {{ $faq['q'] }}
                                </span>
                                <span class="shrink-0 w-8 h-8 rounded-full border border-hairline dark:border-pine-soft
                                             grid place-items-center mt-0.5 transition-colors"
                                      :class="open ? 'bg-marigold border-marigold' : ''"
                                      aria-hidden="true">
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200"
                                         :class="open ? 'rotate-45 text-ink' : 'text-moss dark:text-bone-dim'"
                                         viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M8 3v10M3 8h10" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </button>
                        </h3>

                        <div id="faq-panel-{{ $index }}" x-show="open" x-collapse x-cloak>
                            <p class="pb-7 pr-14 text-[0.9375rem] leading-relaxed text-moss dark:text-bone-dim">
                                {{ $faq['a'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
