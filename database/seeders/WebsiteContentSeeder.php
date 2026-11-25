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
 * Seeds public website content for the sample professional certification body (LSP).
 *
 * Idempotent: safe to re-run. Every record is matched on a natural key
 * (slug / company_name / order) and updated in place.
 *
 * Everything here is SAMPLE content. The organisation is fictional, the contact
 * details use reserved example domains, and no licence, accreditation, statistic
 * or testimonial is claimed. Replace it from the admin panel before going live.
 *
 * NOTE: DatabaseSeeder uses WithoutModelEvents, which mutes the `creating`
 * hooks that normally generate uuid/slug/excerpt. Every one of those columns
 * is therefore set explicitly here rather than relied upon.
 *
 * The illustration SVGs (storage/app/public/**) are gitignored like any uploaded
 * media, so this seeder generates them on first run. It never overwrites a file
 * that already exists, because an operator may have replaced it with a real photo.
 */
class WebsiteContentSeeder extends Seeder
{
    private const COMPANY = 'LSP Kompeta Nusantara';

    /** Slugs of the previous "Satu Pintu" public-service sample news, retired by this seeder. */
    private const RETIRED_NEWS_SLUGS = [
        'izin-usaha-mikro-kini-terbit-dalam-tiga-hari-kerja',
        'layanan-adminduk-keliling-menjangkau-dua-belas-kelurahan',
        'jadwal-layanan-selama-libur-nasional-pekan-depan',
        'perbaikan-drainase-jalan-diponegoro-memasuki-tahap-akhir',
        'pendaftaran-pelatihan-keterampilan-digital-dibuka',
        'verifikasi-dokumen-elektronik-dapat-dilakukan-mandiri',
        'penyesuaian-alur-antrean-di-loket-kependudukan',
        'titik-lampu-penerangan-jalan-bertambah-di-enam-ruas',
    ];

    private const RETIRED_CATEGORY_SLUGS = ['perizinan', 'kependudukan', 'infrastruktur', 'program'];

    public function run(): void
    {
        $this->ensureIllustrations();
        $this->seedAboutUs();
        $this->retireOldContent();
        $categories = $this->seedCategories();
        $this->seedNews($categories);
        $this->seedCarousels();
    }

    private function retireOldContent(): void
    {
        News::whereIn('slug', self::RETIRED_NEWS_SLUGS)->delete();
        NewsCategory::whereIn('slug', self::RETIRED_CATEGORY_SLUGS)->delete();
    }

    private function ensureIllustrations(): void
    {
        Storage::disk('public')->makeDirectory('carousels');
        Storage::disk('public')->makeDirectory('news');

        foreach (['skema', 'asesmen', 'jadwal'] as $i => $key) {
            $path = "carousels/berkas-{$key}.svg";
            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $this->carouselSvg($key, $i));
            }
        }

        foreach ($this->categoryIcons() as $slug => $icon) {
            $path = "news/{$slug}.svg";

            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $this->categoryIllustrationSvg($slug, $icon));
            }
        }
    }

    /**
     * Carousel backdrop: pine ground with concentric rosette rings and one stamp.
     * Drawn from circles only, so it stays light and needs no stock imagery.
     */
    private function carouselSvg(string $key, int $variant): string
    {
        $rings = '';
        foreach (range(1, 9) as $n) {
            $r = 60 + $n * 52;
            $dash = 4 + (($n + $variant) % 4) * 6;
            $rings .= "<circle cx=\"1100\" cy=\"260\" r=\"{$r}\" fill=\"none\" stroke=\"#F7F9FC\" stroke-opacity=\"0.10\" stroke-width=\"1.4\" stroke-dasharray=\"{$dash} 9\"/>";
        }
        $rot = 12 + $variant * 9;

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 900" width="1600" height="900" role="presentation">
          <rect width="1600" height="900" fill="#162948"/>
          {$rings}
          <g transform="translate(1180 640) rotate(-{$rot})" fill="none" stroke="#E7BA4B" stroke-width="5">
            <rect x="-170" y="-52" width="340" height="104" rx="10"/>
            <rect x="-158" y="-40" width="316" height="80" rx="6" stroke-width="2"/>
            <text x="0" y="14" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="44" font-weight="800" letter-spacing="6" fill="#E7BA4B" stroke="none">CONTOH</text>
          </g>
        </svg>
        SVG;
    }

    /**
     * One icon per news category, drawn in the same stroke language as the rest of the site.
     *
     * @return array<string, string> category slug => inline SVG path markup
     */
    private function categoryIcons(): array
    {
        return [
            'pengumuman' => '
                <path d="M-90,-10 v40 l50,25 v-90 Z" fill="none" stroke="{paper}" stroke-width="4" stroke-linejoin="round"/>
                <path d="M-40,-45 l90,-35 v140 l-90,-35 Z" fill="none" stroke="{paper}" stroke-width="4" stroke-linejoin="round"/>
                <path d="M-75,30 l10,45" stroke="{paper}" stroke-width="4" stroke-linecap="round"/>
                <path d="M65,-25 a45,45 0 0 1 0,50" fill="none" stroke="{cap}" stroke-width="5" stroke-linecap="round"/>
            ',
            'skema' => '
                <path d="M-60,-90 h90 l30,30 v150 h-120 Z" fill="none" stroke="{paper}" stroke-width="4"/>
                <path d="M30,-90 v30 h30" fill="none" stroke="{paper}" stroke-width="4"/>
                <path d="M-30,-30 h60 M-30,0 h60 M-30,30 h40" stroke="{paper}" stroke-width="4" stroke-linecap="round"/>
                <circle cx="55" cy="60" r="34" fill="{ground}" stroke="{cap}" stroke-width="5"/>
                <path d="M40,60 l10,10 20,-22" fill="none" stroke="{cap}" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
            ',
            'asesmen' => '
                <rect x="-70" y="-85" width="140" height="180" rx="12" fill="none" stroke="{paper}" stroke-width="4"/>
                <path d="M-25,-85 v-14 h50 v14" fill="none" stroke="{paper}" stroke-width="4" stroke-linejoin="round"/>
                <path d="M-42,-40 l8,8 14,-16 M-42,5 l8,8 14,-16 M-42,50 l8,8 14,-16" fill="none" stroke="{cap}" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M0,-38 h40 M0,7 h40 M0,52 h40" stroke="{paper}" stroke-width="4" stroke-linecap="round"/>
            ',
            'jadwal-uji' => '
                <rect x="-95" y="-70" width="190" height="150" rx="14" fill="none" stroke="{paper}" stroke-width="4"/>
                <path d="M-95,-25 h190 M-50,-95 v38 M50,-95 v38" stroke="{paper}" stroke-width="4" stroke-linecap="round"/>
                <circle cx="-45" cy="20" r="9" fill="{paper}"/><circle cx="0" cy="20" r="9" fill="{paper}"/>
                <circle cx="45" cy="20" r="11" fill="none" stroke="{cap}" stroke-width="5"/>
                <circle cx="-45" cy="55" r="9" fill="{paper}"/><circle cx="0" cy="55" r="9" fill="{paper}"/>
            ',
            'sertifikat' => '
                <rect x="-105" y="-70" width="210" height="140" rx="10" fill="none" stroke="{paper}" stroke-width="4"/>
                <path d="M-70,-35 h90 M-70,-8 h60" stroke="{paper}" stroke-width="4" stroke-linecap="round"/>
                <circle cx="62" cy="30" r="30" fill="{ground}" stroke="{cap}" stroke-width="5"/>
                <path d="M48,30 l10,10 18,-20" fill="none" stroke="{cap}" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M50,58 l-10,38 l22,-12 l22,12 l-10,-38" fill="none" stroke="{cap}" stroke-width="4" stroke-linejoin="round"/>
            ',
        ];
    }

    private function categoryIllustrationSvg(string $slug, string $iconTemplate): string
    {
        $ground = '#162948';
        $lift = '#273955';
        $cap = '#E7BA4B';
        $paper = '#F7F9FC';

        $icon = str_replace(['{paper}', '{cap}', '{ground}'], [$paper, $cap, $ground], $iconTemplate);

        return <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="1200" height="800" role="img" aria-label="Ilustrasi kategori {$slug}">
          <defs>
            <pattern id="dots-{$slug}" width="34" height="34" patternUnits="userSpaceOnUse">
              <circle cx="1" cy="1" r="1.4" fill="{$paper}" opacity="0.18"/>
            </pattern>
            <radialGradient id="glow-{$slug}" cx="50%" cy="42%" r="65%">
              <stop offset="0%" stop-color="{$lift}" stop-opacity="1"/>
              <stop offset="100%" stop-color="{$ground}" stop-opacity="0"/>
            </radialGradient>
          </defs>

          <rect width="1200" height="800" fill="{$ground}"/>
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
        // Demote any pre-existing rows so only the sample LSP is active.
        AboutUs::query()->update(['is_active' => false]);

        AboutUs::updateOrCreate(
            ['company_name' => self::COMPANY],
            [
                'uuid' => (string) Str::uuid(),
                'description' => '<p>'.self::COMPANY.' adalah contoh profil lembaga sertifikasi profesi. '
                    .'Teks ini hanya pengisi dan perlu diganti dengan profil lembaga yang sebenarnya dari panel admin.</p>'
                    .'<p>Lembaga sertifikasi profesi menguji apakah seseorang mampu mengerjakan pekerjaan menurut '
                    .'standar kompetensinya. Penilaian dilakukan oleh asesor terhadap unit kompetensi pada skema '
                    .'sertifikasi, bukan terhadap peserta lain.</p>'
                    .'<p>Hasil asesmen disampaikan sebagai Kompeten atau Belum Kompeten. Bagi asesi yang belum '
                    .'kompeten, asesor menjelaskan unit mana yang masih perlu dipenuhi.</p>',
                'address' => 'Jl. Contoh Raya No. 1, Kota Contoh, 40000',
                'phone' => '(021) 5550 0100',
                'email' => 'layanan@lsp-contoh.example',
                'whatsapp' => '6281100000000',
                'facebook' => 'https://facebook.com/lsp-contoh',
                'instagram' => 'https://instagram.com/lsp-contoh',
                'twitter' => 'https://x.com/lsp-contoh',
                'youtube' => 'https://youtube.com/@lsp-contoh',
                'linkedin' => 'https://linkedin.com/company/lsp-contoh',
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
            'Pengumuman' => 'Informasi resmi dan pemberitahuan dari lembaga.',
            'Skema' => 'Skema sertifikasi baru dan pembaruan unit kompetensi.',
            'Asesmen' => 'Panduan dan informasi seputar pelaksanaan asesmen.',
            'Jadwal Uji' => 'Jadwal dan lokasi uji kompetensi.',
            'Sertifikat' => 'Penerbitan, pengambilan, dan masa berlaku sertifikat.',
        ];

        $map = [];

        foreach ($categories as $name => $description) {
            $slug = Str::slug($name);

            $category = NewsCategory::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $name,
                    'description' => $description,
                    'is_active' => true,
                    'deleted_at' => null,
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
            $existing = News::withTrashed()->where('slug', $slug)->first();

            News::withTrashed()->updateOrCreate(
                ['slug' => $slug],
                [
                    'uuid' => (string) Str::uuid(),
                    'news_category_id' => $categories[$item['category']],
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'content' => $this->paragraphs($item['body']),
                    // Preserve any operator-uploaded image.
                    'image' => $existing?->image ?: 'news/'.$item['category'].'.svg',
                    'published_at' => Carbon::now()->subHours($item['hours_ago']),
                    'is_featured' => $index === 0,
                    'is_active' => true,
                    'deleted_at' => null,
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
     * All sample articles. Dates, quotas and place names are placeholders.
     *
     * @return array<int, array{title: string, category: string, excerpt: string, hours_ago: int, body: array<int, string>}>
     */
    private function newsItems(): array
    {
        return [
            [
                'title' => 'Contoh: skema baru dibuka untuk pendaftaran asesi',
                'category' => 'skema',
                'hours_ago' => 3,
                'excerpt' => 'Contoh pengumuman skema baru. Isi artikel ini pengisi dan perlu diganti dengan informasi skema yang sebenarnya.',
                'body' => [
                    'Ini contoh artikel untuk memperlihatkan tata letak berita. Ganti judul dan isinya dengan pengumuman skema yang sebenarnya dari panel admin.',
                    'Pengumuman skema yang baik memuat nama skema, unit kompetensi yang diuji, persyaratan dasar asesi, dan tanggal pendaftaran. Hindari menyebut nominal atau kuota bila belum pasti.',
                    'Calon asesi dapat membuat akun, memilih skema, lalu mengajukan permohonan beserta bukti pendukung.',
                ],
            ],
            [
                'title' => 'Contoh: cara mengisi asesmen mandiri dengan benar',
                'category' => 'asesmen',
                'hours_ago' => 9,
                'excerpt' => 'Contoh panduan untuk asesi. Asesmen mandiri meminta Anda menilai kemampuan sendiri pada tiap unit kompetensi.',
                'body' => [
                    'Dalam asesmen mandiri, asesi mencentang unit kompetensi yang diyakini mampu dikerjakan, lalu melampirkan bukti untuk setiap unit yang dicentang.',
                    'Centang hanya unit yang memang bisa Anda buktikan. Asesor akan memeriksa bukti tersebut pada tahap asesmen.',
                    'Teks ini contoh. Sesuaikan dengan panduan resmi lembaga.',
                ],
            ],
            [
                'title' => 'Contoh: pengumuman jadwal uji kompetensi bulan ini',
                'category' => 'jadwal-uji',
                'hours_ago' => 26,
                'excerpt' => 'Contoh pengumuman jadwal. Jadwal asli ditambahkan oleh admin lembaga.',
                'body' => [
                    'Jadwal pada artikel ini hanya contoh. Pengumuman jadwal sebaiknya memuat skema, tanggal, tempat uji kompetensi (TUK), dan batas akhir pendaftaran.',
                    'Asesi yang berhalangan hadir dapat menghubungi lembaga lewat kanal resmi pada halaman profil.',
                ],
            ],
            [
                'title' => 'Contoh: panduan pengambilan sertifikat kompetensi',
                'category' => 'sertifikat',
                'hours_ago' => 50,
                'excerpt' => 'Contoh informasi pengambilan sertifikat setelah asesi dinyatakan kompeten.',
                'body' => [
                    'Artikel ini contoh. Ganti dengan tata cara pengambilan sertifikat yang berlaku di lembaga Anda, termasuk dokumen yang perlu dibawa.',
                    'Sebutkan juga masa berlaku sertifikat dan cara memeriksa keasliannya hanya bila fitur itu memang tersedia.',
                ],
            ],
            [
                'title' => 'Contoh: pemberitahuan layanan selama libur nasional',
                'category' => 'pengumuman',
                'hours_ago' => 74,
                'excerpt' => 'Contoh pemberitahuan jam layanan. Ganti dengan tanggal dan kanal kontak yang berlaku.',
                'body' => [
                    'Ini contoh pemberitahuan. Sampaikan tanggal libur, kanal yang tetap aktif, dan kapan permohonan yang masuk mulai diproses kembali.',
                ],
            ],
        ];
    }

    private function seedCarousels(): void
    {
        $slides = [
            [
                'order' => 1,
                'title' => 'Buktikan kompetensi Anda lewat uji bersama asesor',
                'description' => 'Contoh sorotan. Pilih skema, kerjakan asesmen, dan terima keputusan Kompeten atau Belum Kompeten.',
                'button_text' => 'Lihat skema',
                'button_link' => '/skema',
                'image' => 'carousels/berkas-skema.svg',
            ],
            [
                'order' => 2,
                'title' => 'Siapkan bukti sebelum hari uji',
                'description' => 'Contoh sorotan. Dokumen pendukung yang rapi mempercepat asesor memeriksa unit kompetensi Anda.',
                'button_text' => 'Lihat alur',
                'button_link' => '/#alur',
                'image' => 'carousels/berkas-asesmen.svg',
            ],
            [
                'order' => 3,
                'title' => 'Cek jadwal dan lokasi uji terdekat',
                'description' => 'Contoh sorotan. Jadwal uji ditambahkan oleh admin lembaga dari panel.',
                'button_text' => 'Lihat jadwal',
                'button_link' => '/jadwal',
                'image' => 'carousels/berkas-jadwal.svg',
            ],
        ];

        foreach ($slides as $slide) {
            Carousel::updateOrCreate(
                ['order' => $slide['order']],
                [
                    'uuid' => (string) Str::uuid(),
                    'image' => $slide['image'],
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
