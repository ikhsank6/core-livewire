# Design

Dua design system yang terpisah dan tidak boleh dicampur:

1. **Admin panel**: top navigation, header biru. Bagian pertama dokumen ini.
2. **Website publik**: identitas pintu dan loket, nila dengan satu aksen kunyit. Lihat bagian [Website publik](#website-publik) di bawah.

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

Header `z-40` harus lebih tinggi dari bar menu `z-30` supaya dropdown pencarian dan profil tampil di atas bar menu.

## Catatan

- Kelas Tailwind dengan nilai arbitrer baru hanya muncul setelah build. Jalankan `npm run dev` atau `npm run build` setelah mengubah warna.
- Jangan pakai tanda kutip ganda di dalam atribut Alpine (`x-data`, `x-init`, `@click`), termasuk di komentar. Pakai kutip tunggal.
- Ikon menu diambil dari kolom `icon` di tabel `menus` (nama heroicon outline, mis. `home`, `cog-6-tooth`). Jika kosong atau tidak dikenal, dipakai ikon garis tiga (level atas) atau titik (anak menu).

---

# Website publik

Portal publik di `/` (beranda, berita, detail berita, profil). Aset terpisah dari admin: `resources/css/website.css` dan `resources/js/website.js`.

**Identitas: pintu dan loket.** Satu aksen (kunyit, cahaya dari balik pintu) di atas dasar indigo gelap. Dikerjakan dengan aturan antislop mode "selama".

## Warna

Token tema (`:root` dan `.dark` di `website.css`) berganti otomatis mengikuti tema. Konstanta brand tidak berganti.

| Token | Terang | Gelap | Dipakai untuk |
|-------|--------|-------|---------------|
| `page` | `#F5F6F3` | `#0A1126` | Latar halaman |
| `surface` | `#FFFFFF` | `#111B38` | Panel |
| `sunken` | `#ECEEE9` | `#0E1730` | Hover baris |
| `ink` | `#0E1424` | `#F2F5FF` | Judul dan teks kuat |
| `body` | `#3F485C` | `#C3CBE0` | Teks isi |
| `muted` | `#5B6478` | `#94A0BE` | Teks sekunder |
| `line` | `#DADED8` | `#223056` | Garis pemisah |
| `accent-text` | `#8A5C00` | `#FFB81C` | Kategori berita |
| `link` | `#1A3E9A` | `#8DB4FF` | Tautan, fokus keyboard |

Konstanta: `nila #0B1B4D` (hero, navbar, footer, pita gelap), `kobalt #1A3E9A` (warna brand admin), `kunyit #FFB81C` (satu-satunya aksen). Semua pasangan teks sudah dicek lolos WCAG AA (terendah 4,97:1).

## Huruf

- Judul: **Bricolage Grotesque** (sumbu lebar `wdth`, bobot 650 sampai 800)
- Isi: **Plus Jakarta Sans** (dirancang di Jakarta)
- Kelas: `.display`, `.title`, `.subtitle`, `.lead`. Judul memakai `text-wrap: balance`.

## Elemen khas

- **Navbar "papan loket":** bar nila dengan lampu kunyit yang menggantung di tepi atas dan meluncur ke menu yang dihover atau difokus, lalu kembali ke halaman aktif. Logo berupa pintu yang terbuka sedikit saat dihover. Tombol utama berbentuk **karcis** (`.ticket`, bergerigi lewat mask, ring fokus digambar di dalam bentuknya).
- **Latar kawung:** pola batik kawung redup di hero. Salinan terangnya hanya terlihat di sekitar kursor (cahaya dari pintu), dan diam di sisi pintu bila tidak ada kursor. Lihat `.kawung`, `.kawung-lit`, `.kawung-ink`.
- **Pintu lengkung:** slider CMS Carousel di dalam bingkai lengkung (`.arch`) yang naik terbuka sekali saat halaman dimuat, dengan satu lengkung kedua sebagai daun pintu.
- **Footer:** kontak di kiri, satu kolom tautan besar di kanan, lalu baris hak cipta dan media sosial.

## Susunan beranda

1. Hero: judul, karcis, pintu lengkung
2. Layanan: judul menempel di kiri, daftar bergaya buku loket di kanan
3. Perjalanan berkas: pita gelap, tab interaktif (Diajukan, Diverifikasi, Diproses, Terbit) dengan panel contoh
4. Berita: satu besar dan dua baris
5. Profil: pernyataan besar dan papan kontak
6. FAQ, penutup, footer

## Data

Dinamis dari CMS: slider (Carousel), berita, kategori, profil dan kontak (`aboutUs`).
Statis di view: daftar layanan, tahap perjalanan berkas, FAQ. Panel di tahap perjalanan diberi label "Contoh tampilan, bukan data nyata".

**Sengaja tidak ada:** angka statistik, rating, testimoni, dan deretan logo instansi. Semuanya karangan tanpa sumber, dan antislop melarangnya (R-17, R-18, R-36). Tambahkan kembali hanya bila datanya nyata atau diberi label contoh.

Ilustrasi berita dan latar carousel bawaan dibuat `WebsiteContentSeeder` (SVG navy dan biru). Seeder tidak menimpa file yang sudah ada.

## Catatan teknis

- CSS dasar, skala huruf, dan komponen harus berada di `@layer` (base atau components). Di luar layer, aturan itu mengalahkan utilitas Tailwind (misalnya `text-white` atau `hidden`).
- Setelah mengubah kelas Tailwind, jalankan `npm run dev` atau `npm run build`.
- Jangan pakai tanda kutip ganda di atribut Alpine, dan jangan mulai ekspresi `x-init` dengan `try`.
- Gerak yang ada: masuknya pintu, lampu navbar, cahaya kursor, tab, dan accordion. Semuanya berhenti di bawah `prefers-reduced-motion`.

---

# Halaman auth (login, daftar, lupa dan reset sandi, verifikasi surel)

Satu layout bersama: `resources/views/components/layouts/auth.blade.php`. Memakai `app.css` (bukan `website.css`), jadi token ditulis sebagai nilai Tailwind arbitrer dan `<style>` inline di layout. Identitas visualnya sama dengan portal publik.

- **Satu latar penuh, formnya kotak di atasnya.** Latar nila dengan pola kawung (terang di sekitar kursor). Tidak dibagi dua kolom. Di layar `lg` ke atas kartu berada di sisi kanan; di bawah itu kartu di tengah dan ilustrasi disembunyikan.
- **Ilustrasi (digambar sendiri, bukan stok):** seorang warga berdiri di depan pintu (tiga lengkung yang tergambar sekali) sambil memegang map berkas dan menunjuk ke kartu login. Terhubung langsung dengan konsep "satu pintu" dan alur berkas. Antislop R-22 melarang ilustrasi stok generik (Undraw, Storyset), jadi jangan diganti dengan itu. SVG-nya inline di layout, dengan `aria-hidden`.
- **Ikon di form login:** amplop (email) dan gembok (kata sandi) di dalam kolom, berubah warna saat fokus; ikon masuk di tombol, digantikan spinner saat memproses.
- **Kartu form:** putih di mode terang, `#111B38` di mode gelap. Kolom input di mode gelap `#0A1126` supaya terlihat terpisah dari kartu. Tombol utama kunyit dengan teks nila.
- **Kalimat penutup** "Satu akun untuk semua urusan Anda." di bawah logo, hanya tampil bila layar cukup (lebar 1280px dan tinggi 720px ke atas, kelas `.auth-statement`), dan di luar alur supaya tidak menambah tinggi halaman.
- **Aksesibilitas:** label terlihat di setiap kolom, error dengan `role="alert"` dan `aria-invalid`, tombol tampilkan/sembunyikan sandi berlabel dan ber-`aria-pressed`, target sentuh minimal 44px, ring fokus terlihat (di area gelap berwarna kunyit).
- **Sengaja tidak ada:** mockup dashboard, grafik, daftar fitur, dan klaim keamanan di panel samping. Semuanya karangan.
- **Keadaan error di semua form auth sama:** hanya border dan ring fokus yang menjadi merah (`border-red-400`, di mode gelap `red-500`), latar kolom tidak berubah, dan pesan kecil (`text-xs`, merah) dengan ikon tanda seru di bawah kolom. Jangan menambah latar merah muda.
- Pesan error bawaan komponen Login sudah berbahasa Indonesia.

