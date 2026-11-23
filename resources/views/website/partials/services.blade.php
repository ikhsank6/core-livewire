@php
    // Static content: there is no Layanan model yet. The heading count below is
    // derived from this list so the two can never disagree.
    $services = [
        ['category' => 'Perizinan',    'title' => 'Izin usaha dan operasional',
         'body' => 'Izin usaha mikro, reklame, dan bangunan.'],
        ['category' => 'Kependudukan', 'title' => 'Dokumen kependudukan',
         'body' => 'Kartu keluarga, akta kelahiran, akta kematian, dan perekaman KTP elektronik.'],
        ['category' => 'Perpajakan',   'title' => 'Pajak dan retribusi',
         'body' => 'Pendaftaran objek pajak, penetapan, dan pembayaran retribusi daerah.'],
        ['category' => 'Pengaduan',    'title' => 'Pengaduan layanan',
         'body' => 'Sampaikan keluhan atas layanan atau fasilitas publik. Setiap laporan mendapat nomor tindak lanjut.'],
        ['category' => 'Informasi',    'title' => 'Informasi publik',
         'body' => 'Permohonan informasi publik melalui pejabat pengelola informasi dan dokumentasi.'],
        ['category' => 'Verifikasi',   'title' => 'Verifikasi dokumen',
         'body' => 'Periksa keaslian dokumen terbitan portal dengan kode verifikasinya.'],
    ];

    $numberWords = [1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat', 5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan'];
    $count = $numberWords[count($services)] ?? count($services);
@endphp

<section id="layanan" class="scroll-mt-16 py-20 md:py-28">
    <div class="wrap grid gap-12 lg:grid-cols-[minmax(0,23rem)_minmax(0,1fr)] lg:gap-20">

        <div class="lg:sticky lg:top-28 lg:self-start">
            <h2 class="title">{{ $count }} jenis urusan, satu akun.</h2>
            <p class="lead mt-5 max-w-[34ch]">
                Pilih urusan Anda. Data diri cukup diisi sekali saat membuat akun.
            </p>
        </div>

        <ul class="border-t border-line">
            @foreach($services as $service)
                <li class="border-b border-line">
                    <a href="{{ route('login') }}"
                       class="group grid gap-x-8 gap-y-2 py-7 px-1 sm:grid-cols-[8rem_minmax(0,1fr)_auto] sm:items-baseline
                              transition-colors duration-200 hover:bg-sunken sm:px-4 sm:-mx-4">
                        <span class="text-sm font-medium text-muted">{{ $service['category'] }}</span>

                        <span class="min-w-0">
                            <span class="subtitle block text-ink transition-transform duration-200 group-hover:translate-x-1">{{ $service['title'] }}</span>
                            <span class="mt-2 block max-w-[52ch] text-[0.9375rem] leading-relaxed">{{ $service['body'] }}</span>
                        </span>

                        <span class="mt-2 sm:mt-0 text-[0.9375rem] font-semibold text-link underline decoration-transparent underline-offset-[6px]
                                     transition-colors group-hover:decoration-current">
                            Ajukan
                            <span class="sr-only">{{ $service['title'] }}</span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
