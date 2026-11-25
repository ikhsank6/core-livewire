# Design

Dua design system yang terpisah dan tidak boleh dicampur:

1. **Admin panel**: top navigation, header biru. Bagian pertama dokumen ini.
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

| Elemen | Nilai | Kelas |
|--------|-------|-------|
| Background | `#1a3e9a` | `bg-[#1a3e9a]` |
| Border bawah | `#2450b4` | `border-[#2450b4]` |
| Teks utama (nama app, user) | putih | `text-white` |
| Tagline, role user | biru muda 80% | `text-blue-200/80` |
| Tanggal | biru muda 90% | `text-blue-100/90` |
| Ikon pencarian, tombol hapus | biru muda 70% | `text-blue-200/70` |
| Placeholder pencarian | biru muda 60% | `placeholder-blue-200/60` |
| Input pencarian | `white/10`, border `white/20` | `bg-white/10 border-white/20` |
| Input pencarian (fokus) | border `white/60`, ring `white/40` | `focus:border-white/60 focus:ring-white/40` |
| Hint `Ctrl+K` | `white/10`, border `white/20`, teks `blue-100/80` | |
| Ikon lonceng | `blue-100/80`, hover putih | `text-blue-100/80 group-hover:text-white` |
| Hover tombol (lonceng, profil) | `white/10` | `hover:bg-white/10` |

Warna `#1a3e9a` diambil dari swatch yang dipilih (sampel piksel `#1a3e9a`, sedikit gradasi ke `#1b42a2`). Header memakai warna yang sama di light dan dark mode.

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

### Header biru (3D halus)

Header `bg-linear-to-b` dari `#2349ad` lewat `#1a3e9a` ke `#173684` (warna dasar `#1a3e9a` tetap di tengah), bibir bawah `border-b-2 #102a6e`, highlight atas `inset 0 1px 0 white/22`, dan bayangan jatuh `0_6px_18px_-4px`. Kolom pencarian dibuat masuk ke dalam (`inset_0_2px_5px` gelap, latar `#0f2a78/35`), dan hint `Ctrl+K` menjadi keycap kecil (gradien putih tipis, bayangan bawah). Teks putih di atas bagian paling terang (`#2349ad`) tetap lolos kontras.

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
- **Sakelar tema (website dan auth):** dua tombol bulat 28px (ikon 14px) dalam track cekung `#3E4D68` setinggi 36px (`.theme-track` di website, kelas Tailwind setara di `auth.blade.php`); yang aktif menyala emas dengan ikon navy (`aria-pressed`). Area klik tiap tombol diperluas ke atas dan bawah (`before:-inset-y-2`, tinggi sekitar 44px) tanpa menambah ukuran tampil. Di auth, tema diganti lewat `$store.theme.value = mode`, tanpa store lewat `localStorage` dan kelas `dark`. "Masuk" di navbar berupa tautan biasa (garis emas saat hover), mengikuti referensi.
- **Footer:** kontak di kiri, satu kolom tautan besar di kanan, lalu baris hak cipta dan media sosial.

## Susunan beranda

1. Hero: judul, kolom cari, sertifikat spesimen, tiga tautan cepat (skema, alur, jadwal). **Muat pertama tidak boleh ada yang terpotong:** di layar `lg` ke atas hero setinggi layar dikurangi header (`min-h-[calc(100svh-3.3125rem)]`), konten di tengah dan pita tautan cepat menempel di dasar. Ukuran judul mengikuti tinggi layar (`min(3.6vw, 6.4vh)`). Di tinggi layar 760px ke bawah tag bidang disembunyikan dan padding dirapatkan; di 620px ke bawah sertifikat diperkecil. Sertifikat diam (tanpa melayang). Animasi centang unit (tergambar satu per satu) dan segel KOMPETEN (mendarat sekali) **hanya jalan pada muat pertama dalam satu sesi tab**: skrip kecil di awal `partials/hero` memakai `sessionStorage` (`lspHeroPlayed`) dan menambah kelas `hero-played` pada `<html>` sebelum hero tergambar; kelas itu mematikan animasi dan langsung menampilkan hasil akhir. Muat ulang dan kunjungan berikutnya di tab yang sama tidak memutar ulang. Bila storage diblokir, animasi tetap jalan seperti biasa.
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
