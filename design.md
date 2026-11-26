# Design

Dua design system yang terpisah dan tidak boleh dicampur:

1. **Admin panel**: top navigation, header navy yang sama dengan navbar website. Bagian pertama dokumen ini.
2. **Website publik dan auth**: identitas lembaga sertifikasi profesi ("berkas asesmen"), warna dari logo LSP (biru, biru langit, satu aksen emas). Lihat bagian [Website publik](#website-publik) di bawah.

---

# Admin Panel

Warna dan tampilan layout admin (top navigation). Berlaku untuk `resources/views/components/layouts/app.blade.php` dan `resources/views/partials/admin-topnav.blade.php`.

## Layout

Dua baris tetap (fixed) di atas halaman, tanpa sidebar:

| Baris | Tinggi | Isi |
|-------|--------|-----|
| Header | `h-16` (64px) | Logo, pencarian menu (Ctrl+K), tanggal, notifikasi, profil |
| Bar menu (lg+) | `h-12` (48px) | Menu dari master, dropdown grup, "More" |

Konten: `pt-16` di mobile, `lg:pt-28` di desktop. Di bawah `lg`, bar menu diganti drawer vertikal yang dibuka dari hamburger.

## Warna header

Header admin memakai warna yang sama dengan navbar website (`partials/nav`), di light maupun dark mode.

| Elemen | Nilai | Kelas |
|--------|-------|-------|
| Background | gradien `#273955` ke `#162948` | `bg-linear-to-b from-[#273955] to-[#162948]` |
| Border bawah | `#0D192B` | `border-b-2 border-[#0D192B]` |
| Highlight dan bayangan | sama dengan navbar website | `shadow-[inset_0_1px_0_rgba(255,255,255,0.1),0_10px_20px_-8px_rgba(4,14,28,0.55)]` |
| Teks dan ikon | putih dengan transparansi | `text-white`, `text-white/85`, `text-white/90` |
| Input pencarian, hover tombol | putih transparan | `white/10`, border `white/20` |

## Warna bar menu

| Elemen | Light | Dark |
|--------|-------|------|
| Background | `#ffffff` (`bg-white`) | `#1e1e2d` (`dark:bg-[#1e1e2d]`) |
| Border bawah | `gray-200` | `white/6` |
| Item tidak aktif | `gray-500` | `gray-400` |
| Item aktif / hover / dropdown terbuka | `gray-900` | `white` |
| Garis bawah item aktif | `blue-600` (`h-0.5`, rounded) | sama |
| Ikon | mengikuti warna teks (`currentColor`) | sama |

## Warna dropdown ("grup" dan "More")

| Elemen | Light | Dark |
|--------|-------|------|
| Background | `#ffffff` | `#1e1e2d` |
| Border | `gray-200` | `white/10` |
| Link biasa | `gray-600`, hover `bg-gray-100` + `gray-900` | `gray-300`, hover `bg-white/6` + `white` |
| Link aktif | `bg-blue-50`, `text-blue-700`, semibold | `bg-blue-600/20`, `text-blue-300` |
| Label grup di "More" | `gray-400`, uppercase | `gray-500` |

## Warna halaman

| Elemen | Light | Dark |
|--------|-------|------|
| Background halaman | `gray-50` | `#1b1c22` |
| Teks | `gray-900` | `white` |

Token Metronic admin (`resources/css/app.css`): primary `#1b84ff`, success `#17c653`, danger `#f8285a`, warning `#f6b100`, dark bg `#1b1c22`, dark card `#1e1e2d`, dark border `#2d2d3a`.

## Kedalaman (bayangan dan lekukan)

Header dan bar menu ditumpuk dengan bayangan supaya terlihat berlapis.

| Elemen | Efek |
|--------|------|
| Header (`z-40`) | `shadow-[0_6px_18px_-4px_rgba(10,25,70,0.55)]` |
| Bar menu (`z-30`) | `rounded-b-2xl`, garis highlight tepi atas, bayangan lembut ke bawah |
| Bar menu, light | `shadow-[inset_0_1px_0_rgba(255,255,255,0.9),0_10px_22px_-8px_rgba(15,23,42,0.28)]` |
| Bar menu, dark | `dark:shadow-[inset_0_1px_0_rgba(255,255,255,0.06),0_10px_22px_-8px_rgba(0,0,0,0.7)]` |
| Dropdown | `shadow-[0_16px_36px_-10px_rgba(15,23,42,0.35)]`, `rounded-xl` |

### Header navy (3D halus)

Gradien navy dengan bibir bawah gelap, highlight atas `inset 0 1px 0 white/10`, dan bayangan jatuh. Kolom pencarian dibuat masuk ke dalam, dan hint `Ctrl+K` menjadi keycap kecil.

### Item menu aktif: tab cekung sampai dasar bar dan garis aksen

Item aktif berupa tab cekung yang turun sampai dasar bar, dengan garis aksen biru tepat di tepi bawah. Pola ini sama dengan navbar website sebelumnya. Dikendalikan helper `keycap(on)` di `x-data` pada `partials/admin-topnav`.

| Keadaan | Efek |
|---------|------|
| Ukuran | `mt-[3px] h-[43px]` di dalam isi bar 46px: jarak atas 3px, dasar tab rata dengan dasar bar (diukur: dasar tab dan garis biru sama-sama di 110px) |
| Sudut | Hanya sudut atas, `rounded-t-lg`. Sudut bawah lurus (`rounded-b-none`) dan tanpa border bawah (`border-b-0`) karena menyatu dengan dasar bar |
| Aktif | Teks `gray-900` (gelap: putih), latar `gray-900/[0.045]` (gelap: `black/25`), border `gray-900/[0.07]`, bayangan inset merata `0 0 6px` ditambah `0 2px 3px` |
| Garis aksen | `h-0.5 bg-blue-600` di `bottom-0` dengan `inset-x-3.5` dan `rounded-t-full`. Hanya bila `item.active` |
| Dropdown terbuka | Tab cekung yang sama, tanpa garis aksen |
| Hover | Gradien tipis dan bayangan luar tipis |
| Ditekan (`active:`) | Tab cekung yang sama |
| Bar | Gradien `white` ke `gray-50`, bibir bawah `border-b-2`, highlight atas, bayangan jatuh lembut |

Dicoba dan dibuang: pil `h-9` (tepi atas tertimpa bayangan header dan tepi bawah tidak terlihat, jadi terlihat terpotong), pil `h-full` menempel ke header, dan pil `rounded-xl` yang melayang (sudut terlalu bulat dan garis biru tidak mepet bawah). Baris pengukur "More" memakai `px-3.5` yang sama dengan item.

Header `z-40` harus lebih tinggi dari bar menu `z-30` supaya dropdown pencarian dan profil tampil di atas bar menu.

## Catatan

- Kelas Tailwind dengan nilai arbitrer baru hanya muncul setelah build. Jalankan `npm run dev` atau `npm run build` setelah mengubah warna.
- Jangan pakai tanda kutip ganda di dalam atribut Alpine (`x-data`, `x-init`, `@click`), termasuk di komentar. Pakai kutip tunggal.
- Ikon menu diambil dari kolom `icon` di tabel `menus` (nama heroicon outline, mis. `home`, `cog-6-tooth`). Jika kosong atau tidak dikenal, dipakai ikon garis tiga (level atas) atau titik (anak menu).

---

# Website publik

Portal publik di `/` (beranda, berita, detail berita, profil). Aset terpisah dari admin: `resources/css/website.css` dan `resources/js/website.js`.

**Tema: lembaga sertifikasi profesi (LSP).** Identitas "berkas asesmen": dasar navy, kertas, dan satu aksen emas. Seluruh palet diambil dari logo LSP (nilai diukur dari piksel logo: biru `#2D68A7`, biru langit `#4BA5DA`, emas `#E7BA4B`; merah marun `#7F2320` hanya detail kecil di logo dan sengaja tidak dipakai agar aksen tetap satu). Dikerjakan dengan aturan antislop mode "selama". Riset komponen mengacu pada pola situs lembaga sertifikasi (BNSP dan LSP): tautan cepat tugas utama, skema, alur sertifikasi, jadwal uji, berita, FAQ, kontak.

## Warna

Token tema (`:root` dan `.dark` di `website.css`) berganti otomatis mengikuti tema. Konstanta brand tidak berganti.

Konstanta (`@theme` di `website.css`): `brand #2D68A7` (biru logo: tautan, tinta stempel, bingkai navbar), `sky #4BA5DA` (biru langit logo: tautan dan fokus di atas gelap), `navy #162948` (navy navbar: hero, footer, pita gelap), `navy-2 #273955` (panel di atas pita gelap), `gold #E7BA4B` (satu-satunya aksen: tombol utama, segel), `gold-deep #B8901F` (tepi bawah tombol), `gold-soft #F0CB6B` (teks dan fokus di atas gelap). Teks di atas emas memakai `#14213A` (8,81:1). Emas tidak dipakai sebagai teks di atas terang (kontras rendah): untuk itu dipakai `brand`. Netral terang digeser ke biru-abu (`page #F3F7FB`, `ink #0F2036`, `body #33475E`, `muted #55697F`, `line #D3DFEA`); netral gelap mengikuti admin. Token semantik ada di bagian atas `website.css`. Jika mengganti warna, cek ulang kontras (WCAG AA).

## Huruf

Satu keluarga saja: **Inter** (400 sampai 800), sama dengan panel admin. Judul memakai bobot 800 dengan tracking rapat dan `text-wrap: balance`. Kelas: `.display`, `.title`, `.subtitle`, `.lead`. Jangan menambah keluarga huruf kedua.

## Elemen khas

- **Latar guilloche:** rosette garis halus seperti cetakan pengaman sertifikat, dari `public/images/guilloche.svg` yang dipakai sebagai **mask** (`.rosette`), jadi warnanya mengikuti `currentColor`. Berputar beberapa derajat mengikuti kursor (`--rot`).
- **Navbar:** bar gelap navy (gradien `navy-2` ke `navy`, warna diukur dari referensi: `#273955` ke `#162948`, garis bawah `#0D192B`) setinggi 52px, sama di mode terang dan gelap. Detail di poin berikutnya.
- **Tombol utama `.btn-accent`:** emas dengan teks navy dan bayangan bawah yang menekan saat diklik.
- **Sertifikat spesimen di hero:** isi pengisi saja (nama, nomor, tanda tangan) dan diberi label "Contoh". Stempel KOMPETEN mendarat sekali (`.stamp`).
- **Navbar, penanda 3D (`.nav-stamp`):** satu penanda meluncur ke item yang di-hover atau difokus. Ukuran: `top: 3px` dan `height: calc(100% - 6px)` (hampir penuh dari atas ke bawah bar, jarak 3px), `border-radius` 5px (sengaja kecil). Saat meluncur ia **timbul**: gradien putih transparan, highlight atas, tepi bawah emas 3px, bayangan jatuh. Di halaman aktif ia **tertekan** (`.is-pressed`): latar `#13223B`, bayangan inset di empat sisi, border tipis, dan garis emas 2px di dalam (`::after`, `bottom: 4px`, inset 10px kiri-kanan). Status `pressed` ada di `x-data` pada `partials/nav` (benar bila item yang ditempati bertanda `aria-current=page`). Teks menu `#B9BFC7` (aktif dan hover putih), kontras 6,29:1 di atas bagian terang bar.
- **Sakelar tema (website dan auth):** satu tombol (`role="switch"`, `aria-checked`) berupa track cekung `#3E4D68` setinggi 36px (`.theme-track` di website, kelas Tailwind setara di `auth.blade.php`) berisi dua ikon, matahari dan bulan (14px). Knob emas 28px (`.theme-knob`) berada di belakang ikon tema aktif dan bergeser 29px saat diklik; ikon aktif berwarna navy, yang lain `#D5DAE3`. Area klik diperluas ke atas dan bawah (`before:-inset-y-2`) tanpa menambah ukuran tampil. Di auth, tema diganti lewat `$store.theme.value = mode`, atau lewat `localStorage` dan kelas `dark` bila store tidak ada. Sempat dicoba dua tombol terpisah, satu tombol bulat dengan satu ikon, dan track dengan satu ikon di knob; semuanya dibuang demi switch dua ikon ini.
- **Navbar website di ponsel:** baris header memakai `gap-3` (`sm:gap-6`), nama lembaga `truncate` dengan ukuran 15px (`sm:text-lg`), dan tombol Menu hanya ikon di bawah `sm` (label tetap ada untuk pembaca layar lewat `aria-label`). Tanpa itu, tombol Menu terdorong keluar layar di lebar 390px.
- **Skema dan jadwal punya halaman sendiri.** Beranda hanya memuat ringkasan: 6 skema (kartu padat, tiga kolom) dengan tautan per bidang dan tombol "Lihat semua N skema", serta 5 jadwal terdekat dengan tombol "Lihat semua N jadwal". Daftar lengkap ada di `/skema` (pencarian, filter bidang dengan hitungan, dua kolom, "Tampilkan N skema lainnya" bertahap, status kosong; membaca `?q=` dan `?bidang=` dari hero) dan `/jadwal` (pencarian, filter pendaftaran, paginasi). Dengan 30 skema, `/skema` hanya menampilkan 10 pertama. Data contoh berasal dari satu sumber (`App\Support\SampleContent`, `config/sample-schemes.php`) sehingga beranda dan halaman lengkap tidak berbeda.
- **Sertifikat contoh di hero:** kertas lanskap coklat. Perkamen `#FBF3E2` ke `#E6D1A4`, tinta `#3B2412`, garis dan pita kisi `#7A4B24`, segel emas dengan cincin coklat `#8A5A2B`. Teks sekunder `#75573A` di atas perkamen.
- **Paginasi berita** (`vendor/pagination/website`): 8 berita per halaman. Dari `sm`: Sebelumnya, nomor halaman, Berikutnya. Di ponsel: ikon panah dan teks "Halaman X dari Y" (deretan nomor tidak muat di 390px). Bila hanya satu halaman, tidak ada kontrol, hanya baris "Menampilkan N berita". Filter `?category=` ikut terbawa saat pindah halaman.
- **Header halaman dalam** (`partials/page-header`) dipadatkan: sekitar 160px (breadcrumb, judul 28 sampai 40px, satu baris subjudul). Konten di bawahnya mulai dengan `pt-8 md:pt-10`.
- **Tombol Masuk di navbar** (`.btn-login`): emas, tinggi 36px, ikon masuk 16px, bibir bawah 2px yang menekan saat diklik. Tanpa bayangan besar dan tanpa efek terangkat.
- **Footer:** kontak di kiri, satu kolom tautan besar di kanan, lalu baris hak cipta dan media sosial.

## Susunan beranda

1. Hero: judul, kolom cari, sertifikat spesimen, tiga tautan cepat (skema, alur, jadwal). **Muat pertama tidak boleh ada yang terpotong:** di layar `lg` ke atas hero setinggi layar dikurangi header (`min-h-[calc(100svh-3.3125rem)]`), konten di tengah dan pita tautan cepat menempel di dasar. Ukuran judul mengikuti tinggi layar (`min(3.6vw, 6.4vh)`). Di tinggi layar 760px ke bawah tag bidang disembunyikan dan padding dirapatkan; di 620px ke bawah sertifikat diperkecil. Sertifikat diam (tanpa melayang). Animasi centang unit dan segel KOMPETEN **berputar terus** dalam satu siklus 7 detik (`animation-iteration-count: infinite`): tiga centang tergambar berurutan (0 sampai 25% siklus), segel mendarat dengan sedikit pantulan (27 sampai 45%), hasil ditahan sampai 89%, lalu keduanya mereda (89 sampai 100%) dan siklus mulai lagi. Waktu tiap centang diatur lewat persentase keyframe (`check-loop-1` sampai `check-loop-3`), bukan `animation-delay`, karena delay hanya berlaku sekali. Yang bergerak hanya centang dan segel; kartu sertifikat tetap diam. Di bawah `prefers-reduced-motion` animasi mati dan keadaan akhir langsung ditampilkan. Sempat dicoba sekali per muat dan sekali per sesi tab; keduanya dibuang karena Anda ingin gerak yang terus berulang.
2. Skema sertifikasi (`#skema`): daftar dengan filter bidang (data contoh, berlabel)
3. Alur sertifikasi (`#alur`): langkah bernomor, lalu pita gelap Kompeten (K) dan Belum Kompeten (BK)
4. Jadwal uji (`#jadwal`): contoh berlabel
5. Sorotan: slider CMS Carousel (tidak tampil bila tidak ada slide aktif)
6. Berita: satu besar dan dua baris
7. Profil: pernyataan besar dan papan kontak
8. FAQ (`#faq`), penutup, footer

## Data

Dinamis dari CMS: slider (Carousel), berita, kategori, profil dan kontak (`aboutUs`).
Statis di view: daftar skema, langkah alur, jadwal, FAQ. Semuanya diberi label "Contoh" yang terlihat.

**Sengaja tidak ada:** angka statistik, rating, testimoni, deretan logo instansi, dan klaim kepatuhan seperti "berlisensi BNSP" atau "terakreditasi". Semuanya tidak boleh muncul sebelum datanya nyata (antislop R-17, R-18, R-36). Fitur seperti "cek keaslian sertifikat" dan "cari asesor" juga tidak dibuat karena tidak ada datanya.

`WebsiteContentSeeder` mengisi data contoh LSP fiktif ("LSP Kompeta Nusantara", domain surel `.example`). Seeder menimpa gambar carousel urutan 1 sampai 3, menghapus (soft delete) berita dan kategori contoh lama bertema layanan publik, dan tidak menimpa file ilustrasi yang sudah ada.

## Catatan teknis

- CSS dasar, skala huruf, dan komponen harus berada di `@layer` (base atau components). Di luar layer, aturan itu mengalahkan utilitas Tailwind (misalnya `text-white` atau `hidden`).
- Setelah mengubah kelas Tailwind, jalankan `npm run dev` atau `npm run build`.
- Jangan pakai tanda kutip ganda di atribut Alpine, dan jangan mulai ekspresi `x-init` dengan `try`.
- Gerak yang ada: rosette mengikuti kursor, bingkai stempel navbar, stempel KOMPETEN, centang segel, tab, dan accordion. Semuanya berhenti di bawah `prefers-reduced-motion`.

---

# Halaman auth (login, daftar, lupa dan reset sandi, verifikasi surel)

Satu layout bersama: `resources/views/components/layouts/auth.blade.php`. Memakai `app.css` (bukan `website.css`), jadi token ditulis sebagai nilai Tailwind arbitrer dan `<style>` inline di layout. Identitas visualnya sama dengan portal publik (Inter saja).

- **Satu latar penuh, formnya kotak di atasnya.** Latar gradien navy (`#273955` ke `#162948`) dengan rosette guilloche (mask `/images/guilloche.svg`, berputar mengikuti kursor). Di layar `lg` ke atas kartu berada di sisi kanan; di bawah itu kartu di tengah dan ilustrasi disembunyikan.
- **Ilustrasi (digambar sendiri, bukan stok):** seorang asesor dengan tanda pengenal merah memegang papan jalan berisi centang dan menunjuk ke kartu login, di samping sertifikat berbingkai (bingkai tergambar sekali, segel merah). Antislop R-22 melarang ilustrasi stok generik, jadi jangan diganti. SVG inline di layout, `aria-hidden`.
- **Ikon di form login:** amplop (email) dan gembok (kata sandi) di dalam kolom, berubah warna saat fokus; ikon masuk di tombol, digantikan spinner saat memproses.
- **Kartu form:** putih di mode terang, `#1e1e2d` (warna kartu admin) di mode gelap. Tombol utama emas `#E7BA4B` dengan teks `#14213A` dan bayangan bawah `#B8901F`. Tautan `#2D68A7` (gelap: `#4BA5DA`).
- **Kalimat penutup** "Satu akun untuk mengikuti uji kompetensi Anda." di bawah logo, hanya tampil bila layar cukup (lebar 1280px dan tinggi 720px ke atas, kelas `.auth-statement`).
- **Aksesibilitas:** label terlihat di setiap kolom, error dengan `role="alert"` dan `aria-invalid`, tombol tampilkan/sembunyikan sandi berlabel dan ber-`aria-pressed`, target sentuh minimal 44px, ring fokus terlihat (di area gelap berwarna `sky` atau `gold-soft`).
- **Sengaja tidak ada:** mockup dashboard, grafik, daftar fitur, dan klaim keamanan di panel samping. Semuanya karangan.
- **Keadaan error di semua form auth sama:** hanya border dan ring fokus yang menjadi merah (`border-red-400`, di mode gelap `red-500`), latar kolom tidak berubah, dan pesan kecil (`text-xs`, merah) dengan ikon tanda seru di bawah kolom. Jangan menambah latar merah muda.
- Halaman reset sandi masih memakai komponen Flux dengan teks bahasa Inggris. Tampilannya mengikuti kartu, tetapi belum diterjemahkan.
