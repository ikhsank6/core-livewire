# Design

Dua design system yang terpisah dan tidak boleh dicampur:

1. **Admin panel**: top navigation, header biru. Bagian pertama dokumen ini.
2. **Website publik dan auth**: identitas lembaga sertifikasi profesi ("berkas asesmen"), hijau rimba dengan satu aksen merah cap. Lihat bagian [Website publik](#website-publik) di bawah.

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

### Item menu bergaya tombol tertekan (inset)

Item yang sedang aktif tampak masuk ke dalam bar, seperti tombol yang ditekan: bayangan ada di keempat sisi (atas, bawah, kiri, kanan), tanpa garis biru di bawah. Dikendalikan helper `keycap(on)` di `x-data` pada `partials/admin-topnav`.

| Keadaan | Efek |
|---------|------|
| Aktif atau dropdown terbuka | Latar `gray-100` (gelap: `black/25`), teks `blue-700` (gelap: putih), bayangan inset `0 0 7px` dan `0 2px 4px` (gelap lebih pekat) |
| Hover | Gradien tipis, bayangan luar tipis (terlihat timbul sedikit) |
| Ditekan (`active:`) | Langsung memakai bayangan inset yang sama dengan item aktif |
| Bar | Gradien `white` ke `gray-50`, bibir bawah `border-b-2`, highlight atas, bayangan jatuh lembut |
| Dropdown | Highlight atas dan bayangan jatuh |

Pil setinggi `h-9` dengan `my-1.5` di dalam bar `h-12`. Garis bawah biru lama (`h-0.5`) sudah dihapus. Kontras teks aktif dicek manual: `blue-700` di atas `gray-100` di atas 4,5:1.

Header `z-40` harus lebih tinggi dari bar menu `z-30` supaya dropdown pencarian dan profil tampil di atas bar menu.

## Catatan

- Kelas Tailwind dengan nilai arbitrer baru hanya muncul setelah build. Jalankan `npm run dev` atau `npm run build` setelah mengubah warna.
- Jangan pakai tanda kutip ganda di dalam atribut Alpine (`x-data`, `x-init`, `@click`), termasuk di komentar. Pakai kutip tunggal.
- Ikon menu diambil dari kolom `icon` di tabel `menus` (nama heroicon outline, mis. `home`, `cog-6-tooth`). Jika kosong atau tidak dikenal, dipakai ikon garis tiga (level atas) atau titik (anak menu).

---

# Website publik

Portal publik di `/` (beranda, berita, detail berita, profil). Aset terpisah dari admin: `resources/css/website.css` dan `resources/js/website.js`.

**Tema: lembaga sertifikasi profesi (LSP).** Identitas "berkas asesmen": dasar hijau rimba, kertas, dan satu aksen merah cap (stempel KOMPETEN). Dikerjakan dengan aturan antislop mode "selama". Riset komponen mengacu pada pola situs lembaga sertifikasi (BNSP dan LSP): tautan cepat tugas utama, skema, alur sertifikasi, jadwal uji, berita, FAQ, kontak.

## Warna

Token tema (`:root` dan `.dark` di `website.css`) berganti otomatis mengikuti tema. Konstanta brand tidak berganti.

Konstanta: `rimba #0D2B27` (hero, footer, pita gelap), `rimba-2 #14403A` (panel di atas pita gelap), `cap #E4472D` (satu-satunya aksen: tombol utama, stempel, segel), `cap-deep #B2301D` (cap sebagai teks di atas kertas, dan bayangan tombol), `cap-soft #FF8A70` (cap sebagai teks atau fokus di atas gelap). Teks di atas `cap` memakai `#1B0B07` (4,77:1). Token semantik (`page`, `surface`, `sunken`, `ink`, `body`, `muted`, `line`, `accent-text`, `link`) ada di bagian atas `website.css`. Jika mengganti warna, cek ulang kontras (WCAG AA).

## Huruf

Satu keluarga saja: **Plus Jakarta Sans** (400 sampai 800). Judul memakai bobot 800 dengan tracking rapat dan `text-wrap: balance`. Kelas: `.display`, `.title`, `.subtitle`, `.lead`. Jangan menambah keluarga huruf kedua.

## Elemen khas

- **Latar guilloche:** rosette garis halus seperti cetakan pengaman sertifikat, dari `public/images/guilloche.svg` yang dipakai sebagai **mask** (`.rosette`), jadi warnanya mengikuti `currentColor`. Berputar beberapa derajat mengikuti kursor (`--rot`).
- **Navbar:** bar kertas dengan tepi bawah bergerigi seperti kertas sobekan (`.torn`, lewat mask). Bingkai stempel (`.nav-stamp`) meluncur ke menu yang dihover atau difokus, lalu kembali ke halaman aktif. Logo berupa segel kompetensi dengan centang yang tergambar ulang saat hover.
- **Tombol utama `.btn-cap`:** merah cap dengan bayangan bawah yang menekan saat diklik.
- **Sertifikat spesimen di hero:** isi pengisi saja (nama, nomor, tanda tangan) dan diberi label "Contoh". Stempel KOMPETEN mendarat sekali (`.stamp`).
- **Footer:** kontak di kiri, satu kolom tautan besar di kanan, lalu baris hak cipta dan media sosial.

## Susunan beranda

1. Hero: judul, tombol daftar, sertifikat spesimen, tiga tautan cepat (skema, alur, jadwal)
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

Satu layout bersama: `resources/views/components/layouts/auth.blade.php`. Memakai `app.css` (bukan `website.css`), jadi token ditulis sebagai nilai Tailwind arbitrer dan `<style>` inline di layout. Identitas visualnya sama dengan portal publik (Plus Jakarta Sans saja).

- **Satu latar penuh, formnya kotak di atasnya.** Latar rimba `#0D2B27` dengan rosette guilloche (mask `/images/guilloche.svg`, berputar mengikuti kursor). Di layar `lg` ke atas kartu berada di sisi kanan; di bawah itu kartu di tengah dan ilustrasi disembunyikan.
- **Ilustrasi (digambar sendiri, bukan stok):** seorang asesor dengan tanda pengenal merah memegang papan jalan berisi centang dan menunjuk ke kartu login, di samping sertifikat berbingkai (bingkai tergambar sekali, segel merah). Antislop R-22 melarang ilustrasi stok generik, jadi jangan diganti. SVG inline di layout, `aria-hidden`.
- **Ikon di form login:** amplop (email) dan gembok (kata sandi) di dalam kolom, berubah warna saat fokus; ikon masuk di tombol, digantikan spinner saat memproses.
- **Kartu form:** putih di mode terang, `#0F2F2B` di mode gelap. Kolom input gelap `#0A1F1C`. Tombol utama merah cap `#E4472D` dengan teks `#1B0B07` dan bayangan bawah `#B2301D`. Tautan `#14403A` (gelap: `#8FD3C2`).
- **Kalimat penutup** "Satu akun untuk mengikuti uji kompetensi Anda." di bawah logo, hanya tampil bila layar cukup (lebar 1280px dan tinggi 720px ke atas, kelas `.auth-statement`).
- **Aksesibilitas:** label terlihat di setiap kolom, error dengan `role="alert"` dan `aria-invalid`, tombol tampilkan/sembunyikan sandi berlabel dan ber-`aria-pressed`, target sentuh minimal 44px, ring fokus terlihat (di area gelap berwarna `cap-soft`).
- **Sengaja tidak ada:** mockup dashboard, grafik, daftar fitur, dan klaim keamanan di panel samping. Semuanya karangan.
- **Keadaan error di semua form auth sama:** hanya border dan ring fokus yang menjadi merah (`border-red-400`, di mode gelap `red-500`), latar kolom tidak berubah, dan pesan kecil (`text-xs`, merah) dengan ikon tanda seru di bawah kolom. Jangan menambah latar merah muda.
- Halaman reset sandi masih memakai komponen Flux dengan teks bahasa Inggris. Tampilannya mengikuti kartu, tetapi belum diterjemahkan.
