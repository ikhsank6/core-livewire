<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use App\Models\Carousel;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Seeds public website content for the "Satu Pintu" portal.
 *
 * Idempotent — safe to re-run. Every record is matched on a natural key
 * (slug / company_name / order) and updated in place.
 *
 * NOTE: DatabaseSeeder uses WithoutModelEvents, which mutes the `creating`
 * hooks that normally generate uuid/slug/excerpt. Every one of those columns
 * is therefore set explicitly here rather than relied upon.
 *
 * The illustration SVGs referenced by carousels/news (storage/app/public/**)
 * are gitignored, like any uploaded media, so this seeder generates them
 * itself on first run — `migrate:fresh --seed` is fully reproducible on a
 * clean clone without a manual asset-generation step.
 */
class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensureIllustrations();
        $this->seedAboutUs();
        $categories = $this->seedCategories();
        $this->seedNews($categories);
        $this->seedCarousels();
    }

    /**
     * Writes the SVG illustrations used by carousels/news to
     * storage/app/public if they are not already there. Never overwrites a
     * file that exists — an operator may have replaced it with a real photo.
     */
    private function ensureIllustrations(): void
    {
        Storage::disk('public')->makeDirectory('carousels');
        Storage::disk('public')->makeDirectory('news');

        if (! Storage::disk('public')->exists('carousels/ground-lattice.svg')) {
            Storage::disk('public')->put('carousels/ground-lattice.svg', $this->groundLatticeSvg());
        }

        foreach ($this->categoryIcons() as $slug => $icon) {
            $path = "news/{$slug}.svg";

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $this->categoryIllustrationSvg($slug, $icon));
            }
        }
    }

    private function groundLatticeSvg(): string
    {
        return <<<'SVG'
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" width="1600" height="900" role="presentation">
          <defs>
            <pattern id="lattice" width="120" height="120" patternUnits="userSpaceOnUse">
              <g fill="none" stroke="#1B84FF" stroke-width="1.5">
                <circle cx="0"   cy="0"   r="42"/>
                <circle cx="120" cy="0"   r="42"/>
                <circle cx="0"   cy="120" r="42"/>
                <circle cx="120" cy="120" r="42"/>
                <circle cx="60"  cy="60"  r="42"/>
              </g>
              <g fill="#1B84FF" opacity="0.5">
                <circle cx="60" cy="60" r="2.5"/>
                <circle cx="0"  cy="0"  r="2.5"/>
                <circle cx="120" cy="120" r="2.5"/>
              </g>
            </pattern>
          </defs>
          <rect width="1600" height="900" fill="#071437"/>
          <rect width="1600" height="900" fill="url(#lattice)"/>
        </svg>
        SVG;
    }

    /**
     * One icon per news category, matching the stroke language used by the
     * layanan cards — so illustration and iconography read as one system
     * rather than stock photography.
     *
     * @return array<string, string> category slug => inline SVG path markup
     */
    private function categoryIcons(): array
    {
        return [
            'perizinan' => '
                <path d="M-60,-90 h90 l30,30 v150 h-120 Z" fill="none" stroke="{bone}" stroke-width="4"/>
                <path d="M30,-90 v30 h30" fill="none" stroke="{bone}" stroke-width="4"/>
                <path d="M-30,-30 h60 M-30,0 h60 M-30,30 h40" stroke="{bone}" stroke-width="4" stroke-linecap="round"/>
                <circle cx="55" cy="60" r="34" fill="{ink}" stroke="{marigold}" stroke-width="5"/>
                <path d="M40,60 l10,10 20,-22" fill="none" stroke="{marigold}" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            ',
            'kependudukan' => '
                <rect x="-100" y="-65" width="200" height="130" rx="14" fill="none" stroke="{bone}" stroke-width="4"/>
                <circle cx="-55" cy="-8" r="26" fill="none" stroke="{bone}" stroke-width="4"/>
                <path d="M-90,45 a35,35 0 0 1 70,0" fill="none" stroke="{bone}" stroke-width="4"/>
                <path d="M0,-25 h75 M0,0 h75 M0,25 h50" stroke="{marigold}" stroke-width="5" stroke-linecap="round"/>
            ',
            'pengumuman' => '
                <path d="M-90,-10 v40 l50,25 v-90 Z" fill="none" stroke="{bone}" stroke-width="4" stroke-linejoin="round"/>
                <path d="M-40,-45 l90,-35 v140 l-90,-35 Z" fill="none" stroke="{bone}" stroke-width="4" stroke-linejoin="round"/>
                <path d="M-75,30 l10,45" stroke="{bone}" stroke-width="4" stroke-linecap="round"/>
                <path d="M65,-25 a45,45 0 0 1 0,50" fill="none" stroke="{marigold}" stroke-width="5" stroke-linecap="round"/>
            ',
            'infrastruktur' => '
                <path d="M-70,80 h140" stroke="{bone}" stroke-width="4" stroke-linecap="round"/>
                <path d="M-40,80 v-140" stroke="{bone}" stroke-width="4" stroke-linecap="round"/>
                <path d="M-40,-60 h95" stroke="{bone}" stroke-width="4" stroke-linecap="round"/>
                <path d="M40,-60 v35" stroke="{marigold}" stroke-width="5" stroke-linecap="round"/>
                <path d="M-40,-60 l-25,25 M-40,-60 l25,25" stroke="{bone}" stroke-width="4" stroke-linecap="round"/>
                <circle cx="40" cy="3" r="7" fill="{marigold}"/>
            ',
            'program' => '
                <path d="M-95,-15 L0,-55 L95,-15 L0,25 Z" fill="none" stroke="{bone}" stroke-width="4" stroke-linejoin="round"/>
                <path d="M-50,5 v45 a50,18 0 0 0 100,0 v-45" fill="none" stroke="{bone}" stroke-width="4"/>
                <path d="M85,-8 v55" stroke="{marigold}" stroke-width="5" stroke-linecap="round"/>
                <circle cx="85" cy="55" r="6" fill="{marigold}"/>
            ',
        ];
    }

    private function categoryIllustrationSvg(string $slug, string $iconTemplate): string
    {
        // Palette follows the public portal design system (design.md): deep navy
        // ground, brand-blue glow, light-blue accent. The placeholder name
        // {marigold} is kept because the icon templates reference it.
        $ink = '#071437';
        $pine = '#1B4FD6';
        $marigold = '#4DA3FF';
        $bone = '#EAF2FF';

        $icon = str_replace(['{bone}', '{marigold}', '{ink}'], [$bone, $marigold, $ink], $iconTemplate);

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="1200" height="800" role="img" aria-label="Ilustrasi kategori {$slug}">
          <defs>
            <pattern id="dots-{$slug}" width="34" height="34" patternUnits="userSpaceOnUse">
              <circle cx="1" cy="1" r="1.4" fill="{$marigold}" opacity="0.35"/>
            </pattern>
            <radialGradient id="glow-{$slug}" cx="50%" cy="42%" r="65%">
              <stop offset="0%" stop-color="{$pine}" stop-opacity="0.9"/>
              <stop offset="100%" stop-color="{$ink}" stop-opacity="0"/>
            </radialGradient>
          </defs>

          <rect width="1200" height="800" fill="{$ink}"/>
          <rect width="1200" height="800" fill="url(#dots-{$slug})"/>
          <circle cx="600" cy="336" r="420" fill="url(#glow-{$slug})"/>

          <g transform="translate(600,340) scale(1.6)" stroke-linecap="round">
            {$icon}
          </g>
        </svg>
        SVG;
    }

    private function seedAboutUs(): void
    {
        // Demote any pre-existing rows so only Satu Pintu is active.
        AboutUs::query()->update(['is_active' => false]);

        AboutUs::updateOrCreate(
            ['company_name' => 'Satu Pintu'],
            [
                'uuid' => (string) Str::uuid(),
                'description' => '<p>Satu Pintu adalah portal layanan terpadu yang menyatukan perizinan, '
                    .'administrasi kependudukan, dan informasi resmi daerah dalam satu alur. Warga tidak perlu '
                    .'lagi berpindah loket, berpindah kantor, atau mengulang berkas yang sama.</p>'
                    .'<p>Setiap pengajuan yang masuk tercatat dengan nomor registrasi, dapat dilacak statusnya '
                    .'secara mandiri, dan diteruskan ke instansi yang berwenang tanpa perantara. Seluruh '
                    .'dokumen keluaran diterbitkan secara elektronik dan dapat diverifikasi keasliannya.</p>'
                    .'<p>Portal ini dikelola bersama oleh instansi daerah dengan dukungan teknis dinas '
                    .'komunikasi dan informatika.</p>',
                'address' => 'Gedung Pelayanan Terpadu Lantai 2, Jl. Diponegoro No. 114, Kec. Bumiwangi, 40285',
                'phone' => '(022) 4237 118',
                'email' => 'layanan@satupintu.go.id',
                'whatsapp' => '6281122330099',
                'facebook' => 'https://facebook.com/satupintu.id',
                'instagram' => 'https://instagram.com/satupintu.id',
                'twitter' => 'https://x.com/satupintu_id',
                'youtube' => 'https://youtube.com/@satupintu',
                'linkedin' => 'https://linkedin.com/company/satupintu',
                'latitude' => -6.90389000,
                'longitude' => 107.61861000,
                'map_url' => 'https://maps.google.com/?q=-6.90389,107.61861',
                'map_embed' => 'https://www.google.com/maps/embed/v1/place?key=&q=-6.90389,107.61861',
                'is_active' => true,
            ]
        );
    }

    /**
     * @return array<string, int> slug => id
     */
    private function seedCategories(): array
    {
        $categories = [
            'Perizinan' => 'Izin usaha, bangunan, reklame, dan operasional.',
            'Kependudukan' => 'Administrasi kependudukan dan dokumen sipil.',
            'Pengumuman' => 'Informasi resmi dan pemberitahuan layanan.',
            'Infrastruktur' => 'Pembangunan dan pemeliharaan fasilitas publik.',
            'Program' => 'Program bantuan, pelatihan, dan pemberdayaan.',
        ];

        $map = [];

        foreach ($categories as $name => $description) {
            $slug = Str::slug($name);

            $category = NewsCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $name,
                    'description' => $description,
                    'is_active' => true,
                ]
            );

            $map[$slug] = $category->id;
        }

        return $map;
    }

    /**
     * @param  array<string, int>  $categories
     */
    private function seedNews(array $categories): void
    {
        foreach ($this->newsItems() as $index => $item) {
            $slug = Str::slug($item['title']);
            $content = $this->paragraphs($item['body']);
            $existing = News::where('slug', $slug)->first();

            News::updateOrCreate(
                ['slug' => $slug],
                [
                    'uuid' => (string) Str::uuid(),
                    'news_category_id' => $categories[$item['category']],
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'content' => $content,
                    // Preserve any operator-uploaded image; fall back to the
                    // category illustration only when there is none.
                    'image' => $existing?->image ?: 'news/'.$item['category'].'.svg',
                    'published_at' => Carbon::now()->subHours($item['hours_ago']),
                    'is_featured' => $index === 0,
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * @param  array<int, string>  $paragraphs
     */
    private function paragraphs(array $paragraphs): string
    {
        return collect($paragraphs)
            ->map(fn (string $p) => '<p>'.$p.'</p>')
            ->implode('');
    }

    /**
     * @return array<int, array{title: string, category: string, excerpt: string, hours_ago: int, body: array<int, string>}>
     */
    private function newsItems(): array
    {
        return [
            [
                'title' => 'Izin usaha mikro kini terbit dalam tiga hari kerja',
                'category' => 'perizinan',
                'hours_ago' => 3,
                'excerpt' => 'Pemangkasan alur verifikasi memotong waktu terbit izin usaha mikro dari '
                    .'empat belas hari kerja menjadi tiga hari kerja, berlaku untuk pengajuan yang '
                    .'berkasnya lengkap sejak awal.',
                'body' => [
                    'Pengajuan izin usaha mikro yang masuk melalui Satu Pintu kini diselesaikan dalam tiga '
                    .'hari kerja. Sebelumnya, pemohon menunggu hingga empat belas hari kerja karena berkas '
                    .'berpindah manual antara tiga meja verifikasi yang terpisah.',
                    'Perubahan ini dimungkinkan setelah verifikasi kelengkapan berkas, pemeriksaan lokasi, '
                    .'dan penerbitan nomor induk berusaha dijalankan pada satu alur yang sama. Petugas dari '
                    .'ketiga fungsi tersebut kini bekerja pada berkas yang sama secara bersamaan, bukan '
                    .'bergantian.',
                    'Pemohon dapat memantau posisi berkasnya melalui nomor registrasi yang diterima saat '
                    .'pengajuan. Apabila terdapat kekurangan dokumen, pemberitahuan dikirim melalui pesan '
                    .'singkat pada hari yang sama, sehingga perbaikan tidak perlu menunggu jadwal kunjungan.',
                    'Layanan ini berlaku untuk usaha dengan modal di bawah satu miliar rupiah di luar tanah '
                    .'dan bangunan. Untuk skala menengah dan besar, tahapan analisis dampak lingkungan masih '
                    .'diperlukan dan waktu penyelesaiannya mengikuti ketentuan yang berlaku.',
                ],
            ],
            [
                'title' => 'Layanan adminduk keliling menjangkau dua belas kelurahan',
                'category' => 'kependudukan',
                'hours_ago' => 9,
                'excerpt' => 'Unit layanan bergerak menyambangi kelurahan yang jaraknya lebih dari '
                    .'delapan kilometer dari gedung pelayanan terpadu, dengan jadwal tetap setiap pekan.',
                'body' => [
                    'Unit layanan administrasi kependudukan bergerak mulai beroperasi di dua belas kelurahan '
                    .'yang berjarak lebih dari delapan kilometer dari gedung pelayanan terpadu. Setiap '
                    .'kelurahan dijadwalkan satu kali dalam sepekan.',
                    'Layanan yang tersedia meliputi perekaman kartu tanda penduduk elektronik, penerbitan '
                    .'kartu keluarga, akta kelahiran, dan akta kematian. Warga cukup membawa dokumen asli '
                    .'beserta satu lembar salinan.',
                    'Jadwal kunjungan per kelurahan diumumkan setiap awal bulan melalui kanal resmi dan '
                    .'papan informasi kelurahan. Warga yang berhalangan pada jadwal tersebut tetap dapat '
                    .'dilayani di gedung pelayanan terpadu pada hari kerja.',
                ],
            ],
            [
                'title' => 'Jadwal layanan selama libur nasional pekan depan',
                'category' => 'pengumuman',
                'hours_ago' => 26,
                'excerpt' => 'Loket fisik tutup selama tiga hari libur nasional. Pengajuan daring tetap '
                    .'diterima dan diproses pada hari kerja berikutnya sesuai urutan masuk.',
                'body' => [
                    'Gedung pelayanan terpadu tutup selama tiga hari libur nasional pekan depan. Seluruh '
                    .'loket fisik, termasuk loket pengambilan dokumen, tidak beroperasi pada periode tersebut.',
                    'Pengajuan melalui portal tetap dapat dilakukan dua puluh empat jam. Berkas yang masuk '
                    .'selama masa libur akan diproses pada hari kerja pertama setelahnya, mengikuti urutan '
                    .'nomor registrasi.',
                    'Untuk keperluan mendesak yang berkaitan dengan akta kematian, petugas piket tetap '
                    .'tersedia melalui nomor layanan darurat yang tercantum pada halaman kontak.',
                ],
            ],
            [
                'title' => 'Perbaikan drainase Jalan Diponegoro memasuki tahap akhir',
                'category' => 'infrastruktur',
                'hours_ago' => 50,
                'excerpt' => 'Pekerjaan saluran sepanjang satu koma empat kilometer ditargetkan rampung '
                    .'akhir bulan. Rekayasa lalu lintas masih berlaku pada ruas antara simpang Bumiwangi '
                    .'dan simpang Pasar Lama.',
                'body' => [
                    'Pekerjaan perbaikan drainase di sepanjang Jalan Diponegoro memasuki tahap akhir. Dari '
                    .'total satu koma empat kilometer saluran yang direncanakan, sekitar delapan puluh '
                    .'persen telah selesai dikerjakan.',
                    'Rekayasa lalu lintas masih diberlakukan pada ruas antara simpang Bumiwangi dan simpang '
                    .'Pasar Lama. Kendaraan roda empat diarahkan melalui Jalan Pahlawan, sementara kendaraan '
                    .'roda dua tetap dapat melintas dengan satu lajur bergantian.',
                    'Pekerjaan dijadwalkan rampung pada akhir bulan ini. Setelah saluran selesai, '
                    .'pengaspalan ulang permukaan jalan akan dimulai dan diperkirakan memakan waktu dua '
                    .'pekan tambahan.',
                ],
            ],
            [
                'title' => 'Pendaftaran pelatihan keterampilan digital dibuka',
                'category' => 'program',
                'hours_ago' => 74,
                'excerpt' => 'Empat ratus kuota tersedia untuk pelatihan pemasaran digital, pembukuan '
                    .'sederhana, dan fotografi produk. Pendaftaran ditutup setelah kuota terpenuhi.',
                'body' => [
                    'Pendaftaran pelatihan keterampilan digital angkatan ketiga dibuka mulai hari ini. '
                    .'Tersedia empat ratus kuota yang terbagi ke dalam tiga kelas: pemasaran digital, '
                    .'pembukuan sederhana, dan fotografi produk.',
                    'Pelatihan berlangsung selama enam pertemuan, dua kali dalam sepekan, bertempat di aula '
                    .'gedung pelayanan terpadu. Peserta tidak dipungut biaya dan memperoleh sertifikat '
                    .'setelah menyelesaikan seluruh pertemuan.',
                    'Persyaratan peserta adalah warga yang berdomisili di wilayah kabupaten, berusia di atas '
                    .'tujuh belas tahun, dan memiliki atau sedang merintis usaha. Pendaftaran ditutup '
                    .'setelah kuota terpenuhi.',
                ],
            ],
            [
                'title' => 'Verifikasi dokumen elektronik dapat dilakukan mandiri',
                'category' => 'perizinan',
                'hours_ago' => 103,
                'excerpt' => 'Setiap dokumen terbitan portal memuat kode verifikasi yang dapat diperiksa '
                    .'keasliannya tanpa perlu datang ke loket.',
                'body' => [
                    'Seluruh dokumen yang diterbitkan melalui Satu Pintu kini memuat kode verifikasi pada '
                    .'sudut kanan bawah. Kode tersebut dapat diperiksa melalui halaman verifikasi pada '
                    .'portal untuk memastikan dokumen benar diterbitkan oleh instansi yang berwenang.',
                    'Hasil pemeriksaan menampilkan nomor registrasi, tanggal terbit, instansi penerbit, dan '
                    .'status berlaku dokumen. Pemeriksaan tidak memerlukan akun dan dapat dilakukan oleh '
                    .'siapa pun yang menerima dokumen tersebut.',
                    'Langkah ini menggantikan praktik legalisasi salinan fisik yang sebelumnya mengharuskan '
                    .'pemohon datang ke loket. Dokumen cetak tetap sah selama kode verifikasinya terbaca.',
                ],
            ],
            [
                'title' => 'Penyesuaian alur antrean di loket kependudukan',
                'category' => 'kependudukan',
                'hours_ago' => 148,
                'excerpt' => 'Antrean dipisah menjadi jalur pengajuan baru dan jalur pengambilan dokumen '
                    .'untuk memangkas waktu tunggu pada jam sibuk.',
                'body' => [
                    'Mulai pekan ini antrean di loket kependudukan dipisah menjadi dua jalur: jalur '
                    .'pengajuan baru dan jalur pengambilan dokumen yang sudah selesai. Pemisahan ini '
                    .'menanggapi penumpukan yang kerap terjadi pada pukul sembilan hingga sebelas pagi.',
                    'Warga yang hanya mengambil dokumen tidak lagi mengantre di belakang pemohon baru yang '
                    .'memerlukan waktu layanan lebih lama. Nomor antrean untuk kedua jalur diambil pada '
                    .'mesin yang sama di pintu masuk.',
                    'Pemantauan pekan pertama menunjukkan waktu tunggu rata-rata pada jalur pengambilan '
                    .'turun dari tiga puluh empat menit menjadi sebelas menit.',
                ],
            ],
            [
                'title' => 'Titik lampu penerangan jalan bertambah di enam ruas',
                'category' => 'infrastruktur',
                'hours_ago' => 196,
                'excerpt' => 'Sebanyak dua ratus delapan belas titik lampu dipasang pada ruas yang '
                    .'sebelumnya dilaporkan gelap oleh warga melalui kanal pengaduan.',
                'body' => [
                    'Sebanyak dua ratus delapan belas titik lampu penerangan jalan umum dipasang pada enam '
                    .'ruas yang sebelumnya dilaporkan gelap. Seluruh titik dipilih berdasarkan laporan warga '
                    .'yang masuk melalui kanal pengaduan sepanjang tahun lalu.',
                    'Lampu yang dipasang menggunakan modul hemat daya dengan sensor cahaya, sehingga menyala '
                    .'dan padam mengikuti kondisi terang lingkungan tanpa pengaturan manual.',
                    'Ruas yang belum tercakup pada tahap ini tetap tercatat dalam daftar tunggu dan akan '
                    .'dikerjakan pada tahap berikutnya sesuai ketersediaan anggaran.',
                ],
            ],
        ];
    }

    private function seedCarousels(): void
    {
        $slides = [
            [
                'order' => 1,
                'title' => 'Satu pintu untuk semua urusan warga',
                'description' => 'Izin, dokumen, dan informasi resmi dalam satu alur yang bisa dilacak '
                    .'dari awal sampai terbit.',
                'button_text' => 'Ajukan layanan',
                'button_link' => '/login',
            ],
            [
                'order' => 2,
                'title' => 'Setiap berkas punya nomor registrasi',
                'description' => 'Pantau posisi pengajuan Anda kapan saja tanpa perlu menelepon atau '
                    .'datang ke loket.',
                'button_text' => 'Lacak pengajuan',
                'button_link' => '/login',
            ],
            [
                'order' => 3,
                'title' => 'Dokumen elektronik yang bisa diverifikasi',
                'description' => 'Kode verifikasi pada setiap dokumen terbitan dapat diperiksa keasliannya '
                    .'oleh siapa pun yang menerimanya.',
                'button_text' => 'Cara verifikasi',
                'button_link' => '/news',
            ],
        ];

        foreach ($slides as $slide) {
            $existing = Carousel::where('order', $slide['order'])->first();

            Carousel::updateOrCreate(
                ['order' => $slide['order']],
                [
                    'uuid' => (string) Str::uuid(),
                    // carousels.image is NOT NULL. Preserve any operator-uploaded image;
                    // fall back to the generated ground texture only when there is none.
                    'image' => $existing?->image ?: 'carousels/ground-lattice.svg',
                    'title' => $slide['title'],
                    'description' => $slide['description'],
                    'button_text' => $slide['button_text'],
                    'button_link' => $slide['button_link'],
                    'is_active' => true,
                ]
            );
        }
    }
}
