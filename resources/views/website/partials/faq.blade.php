@php
    // Static content: there is no FAQ model. Every question comes from the
    // actual application flow (account, processing, missing documents).
    $faqs = [
        ['q' => 'Apakah harus punya akun untuk mengajukan layanan?',
         'a' => 'Ya. Akun diperlukan agar setiap pengajuan terhubung dengan identitas pemohon dan statusnya bisa dilacak. Pendaftaran cukup sekali, memakai nomor induk kependudukan dan alamat surel aktif.'],
        ['q' => 'Berapa lama berkas saya diproses?',
         'a' => 'Itu bergantung pada jenis layanan. Perkiraan waktu setiap layanan ditampilkan sebelum Anda mengirim pengajuan, dan posisi berkas bisa dilihat kapan saja dari akun.'],
        ['q' => 'Bagaimana jika dokumen saya kurang?',
         'a' => 'Anda mendapat pemberitahuan lewat surel dan pesan singkat, lengkap dengan dokumen yang perlu dilengkapi. Perbaikan diunggah langsung ke pengajuan yang sama, tanpa mengulang dari awal.'],
        ['q' => 'Apakah dokumen elektronik sah tanpa legalisasi?',
         'a' => 'Setiap dokumen terbitan portal memuat kode verifikasi yang bisa diperiksa siapa pun lewat halaman verifikasi, jadi salinan fisik tidak perlu dilegalisasi lagi.'],
        ['q' => 'Saya tidak bisa datang pada jam kerja. Ada pilihan lain?',
         'a' => 'Pengajuan lewat portal bisa dilakukan kapan saja. Untuk layanan yang memerlukan kehadiran, jadwal dan lokasinya tercantum di halaman profil.'],
    ];
@endphp

<section id="faq" class="scroll-mt-16 border-t border-line py-20 md:py-28">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,23rem)_minmax(0,1fr)] lg:gap-20">

        <div>
            <h2 class="title">Pertanyaan yang sering diajukan di loket.</h2>
            <p class="lead mt-5 max-w-[34ch]">Belum menemukan jawabannya? Hubungi kami lewat kanal resmi.</p>
            <a href="{{ route('about') }}#kontak" class="btn btn-line btn-lg mt-8">Lihat kontak</a>
        </div>

        <div class="border-t border-line">
            @foreach($faqs as $index => $faq)
                <div x-data="{ open: false }" class="border-b border-line">
                    <h3>
                        <button type="button"
                                @click="open = !open"
                                :aria-expanded="open ? 'true' : 'false'"
                                aria-controls="faq-panel-{{ $index }}"
                                class="group flex w-full items-center justify-between gap-6 py-6 text-left cursor-pointer">
                            <span class="subtitle text-[1.1875rem] transition-colors group-hover:text-link">{{ $faq['q'] }}</span>
                            <span class="shrink-0 text-muted transition-transform duration-300" :class="open ? 'rotate-45 text-ink' : ''" aria-hidden="true">
                                @svg('heroicon-m-plus', 'w-6 h-6')
                            </span>
                        </button>
                    </h3>

                    <div id="faq-panel-{{ $index }}" x-show="open" x-collapse x-cloak>
                        <p class="pb-7 max-w-[62ch] leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
