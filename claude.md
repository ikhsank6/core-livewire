# CLAUDE.md — Project Intelligence

> File ini adalah sumber utama konteks bagi AI assistant (Claude, Gemini, dll.) ketika bekerja dengan codebase ini.
> Ditulis dari perspektif **Senior Fullstack Engineer** yang memahami setiap lapisan arsitektur.
> **Versi dokumen**: v3.0.0

---

## 🏗️ Project Overview

**Nama**: Laravel Livewire CMS Platform
**Versi**: 3.0.0
**Stack**: Laravel 13 + Livewire 4 + Filament Forms 5 + Tailwind CSS 4 + Vite 8 + Alpine.js 3
**Database**: SQLite (development), bisa di-swap ke MySQL/PostgreSQL
**PHP**: >= 8.2
**Tipe Aplikasi**: Content Management System (CMS) dengan role-based access control (RBAC), public-facing website (tema lembaga sertifikasi profesi, identitas "berkas asesmen"), dan admin panel yang dibangun sepenuhnya dengan Livewire full-page components.

---

## 📁 Architecture & Directory Structure

```
app/
├── Actions/           # Single-action invokable classes (LogoutAction, SwitchRoleAction)
│   └── Website/       # Public website controllers (ShowHomePage, ShowNewsList, etc.)
├── Forms/             # Filament Form schema classes (reusable form definitions)
├── Helpers/           # (kosong, reserved untuk helper functions)
├── Http/
│   ├── Controllers/   # Hanya base Controller.php (semua logic di Livewire/Actions)
│   └── Middleware/    # CheckMenuAccess, SecurityHeaders
├── Livewire/          # Livewire full-page components (pengganti Controller tradisional)
│   ├── Auth/          # Login, Register, ForgotPassword, ResetPassword, VerifyEmail, Profile, ChangePassword
│   ├── AboutUs/       # AboutUsIndex
│   ├── Carousels/     # CarouselIndex
│   ├── Concerns/      # Shared traits (HasTableView, WithPasswordValidation, WithNotifications, WithSearchablePagination, WithRateLimiting)
│   ├── Layout/        # NotificationBell, NotificationIndex, Sidebar (Sidebar TIDAK dipakai lagi: navigasi admin pindah ke top bar)
│   ├── Menus/         # MenuIndex, RoleMenuAccess
│   ├── News/          # NewsIndex
│   ├── NewsCategories/# NewsCategoryIndex
│   ├── Roles/         # RoleIndex
│   ├── Settings/      # JobIndex, LogIndex, SystemSettingIndex
│   ├── Users/         # UserIndex
│   └── Dashboard.php
├── Models/            # Eloquent models (10 models)
├── Support/           # SampleContent: data contoh skema dan jadwal (belum ada model; dipakai beranda dan halaman lengkap)
├── Notifications/     # Mail notifications (ResetPasswordQueued, VerifyEmailNotification)
├── Policies/          # Authorization policies (MenuPolicy)
├── Providers/         # AppServiceProvider, RepositoryServiceProvider
├── Repositories/      # Repository pattern implementation
│   └── Contracts/     # Repository interfaces
├── Services/          # Business logic services (MenuService)
└── Traits/            # Shared model traits (HasUuid)

resources/
├── css/
│   ├── app.css        # Admin panel styles (Metronic/Filament/Flux — JANGAN dipakai di website)
│   └── website.css    # Public website styles (design system LSP "berkas asesmen", terpisah dari admin)
├── js/
│   ├── app.js         # Admin panel JS (Highcharts, Flowbite init)
│   └── website.js     # Public website JS (hanya Alpine.js + collapse; efek lain inline di view)
└── views/
    ├── components/
    │   ├── layouts/   # app.blade.php (admin, top navigation), auth.blade.php (split-screen auth)
    │   ├── ui/        # Reusable UI components (modal, table, card, badge, pagination, etc.)
    │   └── emails/    # Email templates
    ├── livewire/      # Blade views untuk setiap Livewire component
    ├── layouts/       # website.blade.php (public website layout)
    ├── website/       # Public website pages (home, about, news)
    │   └── partials/  # nav, hero, skema, alur, jadwal, sorotan, faq, cta, footer, news-card, news-sidebar, page-header, logo, social-links
    └── partials/      # Shared partials (meta, favicon, fonts, alpine-cloak, admin-topnav, dll.)

routes/
├── web.php            # Main route file, loads modular routes
└── modules/
    ├── auth.php       # Authentication routes
    ├── website.php    # Public website routes (rate limited)
    ├── cms.php        # CMS management routes
    ├── master_data.php# Master data routes (users, roles, menus)
    └── settings.php   # Settings routes (system, logs, jobs)
```

---

## 🎯 Core Design Patterns & Conventions

### 1. Repository Pattern (WAJIB diikuti)

Setiap interaksi database **HARUS** melalui Repository, bukan langsung via Eloquent di Livewire component.

```
Interface (Contracts/) → Implementation (Repositories/) → Binding (RepositoryServiceProvider) → Injection (Livewire boot())
```

**BaseRepository** menyediakan method standar:
- `all()`, `paginate()`, `find()`, `findByUuid()`, `findByUuidOrFail()`, `findOrFail()`
- `create()`, `update()`, `delete()` — semua dibungkus `DB::transaction()`
- `search()` — generic search dengan multiple searchable columns

**Rules (TIDAK ADA PENGECUALIAN untuk module/domain model)**:
- Jangan pernah panggil `Model::query()`, `Model::find()`, `Model::where()`, `Model::create()`, dll langsung di Livewire component
- Jangan pernah pakai `DB::table(...)` raw query builder di Livewire component — buat Repository (+ interface) walaupun tabelnya tidak punya Eloquent model (mis. `failed_jobs`)
- Selalu buat interface di `Contracts/` terlebih dahulu
- Daftarkan binding di `RepositoryServiceProvider::$bindings`
- Inject repository via Livewire `boot()` method (bukan constructor)
- **Pengecualian yang diperbolehkan**: pemanggilan Laravel auth facade bawaan (`Auth::attempt()`, `Auth::user()`, `Password::sendResetLink()`, `Password::reset()`) di komponen `Livewire/Auth/*` — facade ini sudah membungkus query-nya sendiri dan bukan akses langsung ke Eloquent model kita.

**Status Audit Kepatuhan** (per modul, cek ulang tiap ada Livewire component baru):
- ✅ Patuh: `AboutUsIndex`, `CarouselIndex`, `Dashboard`, `NotificationBell`, `NotificationIndex`, `MenuIndex`, `RoleMenuAccess`, `NewsIndex`, `NewsCategoryIndex`, `RoleIndex`, `SystemSettingIndex`, `UserIndex`, `Auth/Login`, `Auth/ForgotPassword`, `Auth/ResetPassword`, `Auth/Register`, `Auth/Profile`, `Auth/ChangePassword`
- ❌ **Technical debt — perlu diperbaiki**:
  - `app/Livewire/Auth/VerifyEmail.php` — pakai `\App\Models\User::find($id)` langsung di `mount()`. Harus diganti ke `UserRepositoryInterface::find($id)` (inject via `boot()`).
  - `app/Livewire/Settings/JobIndex.php` — pakai `DB::table('failed_jobs')` raw query untuk list/paginate/delete/truncate. Harus dibuatkan `FailedJobRepositoryInterface` + `FailedJobRepository` (query builder murni, tanpa Eloquent model, tetap dibungkus repository agar reusable & konsisten dengan pattern).

### 2. Livewire Full-Page Components

Project ini **TIDAK menggunakan Laravel Controllers tradisional** untuk admin panel. Semua halaman admin adalah Livewire full-page components.

```php
#[Layout('components.layouts.app')]
#[Title('Page Title')]
class SomePage extends Component implements HasForms
{
    use InteractsWithForms, WithPagination;

    // Repository injection via boot(), BUKAN constructor
    public function boot(SomeRepositoryInterface $repo): void
    {
        $this->someRepo = $repo;
    }
}
```

- Gunakan `#[Layout('components.layouts.app')]` untuk admin pages
- Gunakan `#[Layout('components.layouts.auth')]` untuk auth pages
- Repository di-inject via `boot()` karena Livewire component di-recreate setiap request
- Properties yang di-persist ke URL gunakan `#[Url]`

### 3. Filament Forms Integration

Form schema didefinisikan sebagai **static method** di class terpisah di `app/Forms/`:

```php
class UserForm
{
    public static function schema(): array
    {
        return [TextInput::make('name')->required(), ...];
    }
}
```

**Penting**: Filament Forms hanya digunakan untuk **form schema**, BUKAN Filament panel/admin.

**v5 breaking change (sejak 2026-11-02)**: `Filament\Forms\Form` → `Filament\Schemas\Schema`. Semua `public function form(Schema $form): Schema` di Livewire components — body `$form->schema(...)` tetap jalan (alias bawaan, tidak perlu diganti ke `->components()`). Komponen layout seperti `Section` pindah ke `Filament\Schemas\Components\Section`, dan `Set`/`Get` pindah ke `Filament\Schemas\Components\Utilities\{Set,Get}`. Komponen field (`TextInput`, `Select`, dll.) tetap di `Filament\Forms\Components\*`.

### 4. Actions Pattern

Single-responsibility invokable classes untuk operasi yang bukan CRUD:
- **Website Actions** (`Actions/Website/`): ShowHomePage, ShowSkemaPage, ShowJadwalPage, ShowNewsList, ShowNewsDetail, ShowAboutPage
- **Auth Actions**: LogoutAction, SwitchRoleAction

### 5. UUID & Audit Trail (HasUuid Trait)

Semua model utama menggunakan `HasUuid` trait yang:
- Auto-generate UUID saat `creating`
- Auto-set `created_by` dan `updated_by` dengan nama user yang login
- Menggunakan `uuid` sebagai route key name (bukan `id`)

**Penting**: Saat membuat URL atau route model binding, selalu gunakan `uuid`, bukan `id`.

### 6. Soft Deletes

Semua model utama menggunakan `SoftDeletes`. Migration harus include `$table->softDeletes()`.

---

## 🔐 Authorization System (RBAC)

```
User → has many Roles (via role_user pivot)
User → has one active Role (role_id foreign key)
Role → has many Menus (via role_menu pivot)
Menu → has parent/children (self-referencing, nested tree)
```

1. **Login**: User di-set active role ke default role (`is_default` di pivot `role_user`)
2. **Middleware `menu.access`**: Cek apakah route ada di tabel `menus`, lalu cek akses role
3. **Route yang TIDAK ada di tabel menus**: Accessible oleh semua authenticated user
4. **Switch Role**: Cache menu di-clear saat switch

`MenuService` meng-cache menu tree per role (`menu_tree_role_{id}`) selama 1 jam.

---

## 🌐 Public Website (v3.0.0)

Detail warna, huruf, dan komponen lengkap ada di **`design.md`** (sumber kebenaran desain). Ringkasannya di sini.

### Design System

Tema **lembaga sertifikasi profesi (LSP)**, identitas **"berkas asesmen"**: dasar navy, kertas, dan satu aksen emas. Warna diambil dari logo LSP (biru `#2D68A7`, biru langit `#4BA5DA`, emas `#E7BA4B`). **Sepenuhnya terpisah** dari admin (`app.css`). Dikerjakan dengan aturan **antislop** (mode "selama"). Halaman auth memakai identitas yang sama.

| Token | Value | Catatan |
|-------|-------|---------|
| `brand` | `#2D68A7` | Biru logo: tautan di atas terang, tinta stempel, bingkai navbar |
| `sky` | `#4BA5DA` | Biru langit logo: tautan dan fokus di atas gelap |
| `navy` / `navy-2` | `#162948` / `#273955` | Turunan biru logo yang digelapkan: hero, footer, pita gelap, latar auth / panel di atasnya |
| `gold` | `#E7BA4B` | Satu-satunya aksen (tombol utama `.btn-accent`, segel). Teks di atasnya `#14213A`. JANGAN dipakai sebagai teks di atas terang |
| `gold-deep` / `gold-soft` | `#B8901F` / `#F0CB6B` | Tepi bawah tombol / teks dan fokus di atas gelap |
| `page` / `surface` / `ink` / `body` / `muted` / `line` | berganti per tema | Token semantik di `:root` dan `.dark`, dipetakan via `@theme inline` |
| `--font-display` / `--font-sans` | Inter | **Satu keluarga saja** (400 sampai 800), sama dengan admin |

Semua pasangan teks sudah dicek lolos WCAG AA. Jika mengganti warna, cek ulang kontras.

### Elemen khas

- **Latar guilloche** (`.rosette`): rosette garis halus dari `public/images/guilloche.svg`, dipakai sebagai **mask** (warna lewat `currentColor`), berputar mengikuti kursor lewat `--rot`.
- **Navbar** (`partials/nav`): bar gelap navy setinggi 52px (sama di kedua tema). Penanda tombol `.nav-stamp` meluncur ke menu yang dihover atau difokus: timbul (tepi bawah emas) saat hover, cekung (`.is-pressed`, garis emas di dalam) di halaman aktif; margin 3px atas-bawah, sudut 5px. Sakelar tema berupa satu tombol (`role="switch"`): track cekung (`.theme-track`) berisi ikon matahari dan bulan dengan knob emas yang bergeser (`.theme-knob`). Tombol Masuk memakai `.btn-login` (emas kecil, bibir bawah tipis). Logo = segel kompetensi, centang tergambar ulang saat hover. Tombol utama `.btn-accent` (bayangan bawah menekan saat diklik).
- **Hero**: tinggi mengikuti layar tetapi dibatasi `64rem` agar tidak meregang di monitor sangat tinggi (batas 46rem sebelumnya menyisakan sekitar 37px bagian berikutnya di layar 825px, jadi dinaikkan). Sertifikat spesimen berbentuk kertas lanskap **coklat** (perkamen, pita kisi di tepi, sudut berornamen, segel emas, tanda tangan "Ketua LSP"); hanya bentuknya yang terinspirasi sertifikat kompetensi nasional, tanpa nama, nomor, lambang, atau lembaga asli, dan diberi label ilustrasi. Centang unit dan segel berputar dalam siklus 7 detik; kertasnya diam. Kolom cari dan tag bidang di hero mengarah ke `/skema?q=` dan `/skema?bidang=`.
- **Skema / Alur / Jadwal** (`partials/skema`, `alur`, `jadwal`): data statis berlabel "Contoh". Alur memuat langkah bernomor lalu pita Kompeten (K) / Belum Kompeten (BK).
- **Sorotan** (`partials/sorotan`): slider CMS Carousel; tidak dirender bila tidak ada slide aktif.

### Aturan konten (antislop)

**JANGAN** menambahkan angka statistik, rating, testimoni, deretan logo instansi, atau klaim kepatuhan ("berlisensi BNSP", "terakreditasi") yang karangan (R-17, R-18, R-36). Fitur seperti cek keaslian sertifikat atau pencarian asesor tidak dibuat karena tidak ada datanya. Boleh hanya jika datanya nyata atau diberi label contoh yang terlihat. Teks buatan agent tanpa em dash (R-02), CTA spesifik (bukan "Get Started"), tanpa badge di atas judul.

### Build Pipeline (Vite Entry Points)

```js
// vite.config.js — 4 entry points
input: [
    'resources/css/app.css',    // Admin — Metronic/Flux/Filament
    'resources/js/app.js',      // Admin — Highcharts, Flowbite re-init
    'resources/css/website.css',// Website — design system LSP berkas asesmen
    'resources/js/website.js',  // Website — Alpine.js + collapse
]
```

**PENTING**: Jangan mix kedua CSS. `app.css` mengandung Flux/Filament/Metronic styles yang tidak relevan untuk public website. Kelas Tailwind baru baru muncul setelah `npm run dev` atau `npm run build`.

### Alpine.js di Website

`website.js` hanya memuat Alpine dan plugin `collapse` (bukan CDN). Semua interaksi (bingkai stempel navbar, rosette mengikuti kursor, slider, filter skema, accordion, toggle tema) ditulis **inline** sebagai `x-data` di view, dan masing-masing mengecek `prefers-reduced-motion` sendiri. Tidak ada lagi AOS atau counter.

### Route Structure (rate limited: 60 req/min)

| Route | Action | Description |
|-------|--------|-------------|
| `GET /` | `ShowHomePage` | Beranda: hero, **ringkasan** skema (6) dan jadwal (5 terdekat) dengan tombol ke halaman lengkap, alur, sorotan, berita, profil, FAQ |
| `GET /skema` | `ShowSkemaPage` | Daftar lengkap skema: pencarian, filter bidang (`?q=`, `?bidang=`), dua kolom, tampil bertahap |
| `GET /jadwal` | `ShowJadwalPage` | Jadwal uji lengkap: pencarian, filter pendaftaran, paginasi |
| `GET /news` | `ShowNewsList` | News listing: 8 per halaman, paginasi (`vendor/pagination/website`), sidebar. `?category=` terbawa antar halaman (`withQueryString()`). Satu halaman saja: hanya baris jumlah |
| `GET /news/{slug}` | `ShowNewsDetail` | News detail + related news |
| `GET /about` | `ShowAboutPage` | About us + kontak + Google Maps |

### Reusable Website Partials

| Partial | Props | Keterangan |
|---------|-------|-----------|
| `partials/nav` | — | Navbar gelap navy + panel mobile |
| `partials/hero` | `$carousels`, `$aboutUs` | Judul, tombol daftar, sertifikat spesimen, tautan cepat |
| `partials/skema` | — | Ringkasan di beranda: 6 skema, tautan per bidang, tombol "Lihat semua N skema" (data contoh) |
| `partials/skema-list` | `$skema`, `$showHeading` | Daftar lengkap di `/skema`: cari, filter bidang, tampil bertahap |
| `partials/alur` | — | Langkah sertifikasi + pita K / BK |
| `partials/jadwal` | — | Ringkasan di beranda: 5 jadwal terdekat + tombol ke `/jadwal` (data contoh) |
| `partials/jadwal-list`, `jadwal-row` | `$jadwal`, `$showHeading` | Jadwal lengkap di `/jadwal` dan baris yang dipakai bersama ringkasan |
| `partials/sorotan` | `$carousels` | Slider CMS Carousel |
| `partials/faq` | — | Accordion FAQ (data statis) |
| `partials/cta` | — | Pita penutup |
| `partials/news-card` | `item` (News), `variant` (`card` / `lead` / `row`) | Kartu berita |
| `partials/news-sidebar` | `$categories`, `$recentNews` | Sidebar halaman berita |
| `partials/page-header` | `title`, `breadcrumb`, `subtitle` | Header halaman dalam |
| `partials/footer` | — | Footer: kontak di kiri, satu kolom link besar di kanan, baris hak cipta + sosial. Data dari `$aboutUs` |

---

## 🧭 Admin Layout (Top Navigation)

Layout admin (`components/layouts/app.blade.php`) **tidak lagi memakai sidebar**. Dua baris fixed:

| Baris | Isi |
|-------|-----|
| Header `h-16` (`bg-[#1a3e9a]`, `z-40`) | Logo, pencarian menu (Ctrl+K), tanggal, notifikasi, profil, hamburger (mobile) |
| Bar menu `h-12` (`partials/admin-topnav`, `z-30`, hanya `lg+`) | Menu dari master, dropdown grup, "More" untuk yang tidak muat |

- **Sumber menu**: `MenuService::getMenuTreeForUser()` (RBAC + cache tidak berubah). Layout meratakan tree menjadi `$navItems` (url, ikon, active) untuk Alpine, dan `$flatMenu` untuk pencarian.
- **Ikon**: dari kolom `icon` tabel `menus` (nama heroicon outline). Kosong atau tidak dikenal → ikon garis tiga (level atas) atau titik (anak menu).
- **"More"**: Alpine mengukur lebar tiap item (baris ukur tersembunyi) dan memindahkan yang tidak muat ke dropdown. Bar menu TIDAK boleh `overflow-hidden`, kalau tidak dropdown terpotong.
- **Navigasi**: tautan di Alpine memakai `Livewire.navigate(url)` (menghormati Ctrl/Cmd/Shift-klik).
- **Mobile**: hamburger membuka drawer vertikal (Blade loop biasa dengan `wire:navigate`).
- **Dialog logout**: dialog ringkas khusus di `app.blade.php` (event `open-modal` dengan `name: 'logout-modal'`). Sengaja TIDAK tertutup oleh klik di luar atau Esc; hanya lewat tombol X, Batal, atau Ya, Logout.
- **Footer** menampilkan versi `v3.0.0` (hardcode di `app.blade.php`).
- Warna dan bayangan: lihat `design.md`.

### Halaman Auth (`components/layouts/auth.blade.php`)

Dipakai Login, Register, ForgotPassword, ResetPassword, VerifyEmail. Satu latar penuh (gradien navy + rosette guilloche) dengan **form dalam kotak** di sisi kanan (`lg+`), bukan split dua kolom. Di sisi kiri ada ilustrasi SVG asesor dengan papan jalan berisi centang yang menunjuk ke form, di samping sertifikat berbingkai (digambar sendiri; JANGAN diganti ilustrasi stok, R-22). Tetap memuat `app.css` (bukan `website.css`), jadi token ditulis sebagai nilai Tailwind arbitrer (`bg-[#162948]`, `bg-[#E7BA4B]`) dan `<style>` inline. Kartu form tidak boleh berada di dalam `.auth-dark` (kelas itu mengubah warna ring fokus di area gelap). Detail: `design.md`.

---

## 🎨 Frontend Stack

### Admin Panel

- **Tailwind CSS 4** via `@tailwindcss/vite` plugin
- **Flowbite** (CDN) — header, responsive shell, Alpine.js interactivity
- **Highcharts** (npm, ^12.6.0) — Dashboard donut/bar chart, exposed via `window.Highcharts`
- **Livewire Flux** (v2.10) — Premium UI components (breadcrumbs, badges, form inputs)
- **Filament Forms** — Form schema only (bukan Filament panel)

### Auth Pages

- Layout: satu latar penuh navy dengan rosette guilloche, form dalam kotak di atasnya (bukan split-screen)
- Font: Inter saja via Google Fonts, sama dengan admin dan portal publik
- Theme toggle: icon sun/moon **di dalam** toggle button (bukan di luar)
- Dark mode: didukung penuh dengan smooth transition

### Vite Configuration

```js
plugins: [
    laravel({ input: [/* 4 entry points */], refresh: true }),
    tailwindcss(),
]
```

### Custom UI Components (`views/components/ui/`)

`modal`, `table/` (+ `table/header` dengan slot `searchAction` & `extraActions`), `card`, `badge`, `pagination`, `delete-confirm-modal`, `empty-state`, `avatar`, `button/`, `password-strength`, `select`

#### `<x-ui.select>` — Autocomplete Select (global, reusable)

Komponen select berbasis **Tailwind + Alpine.js** (BUKAN Flux/native `<select>`). Mendukung single & multiple, search/autocomplete bawaan, clearable.

```blade
{{-- Single --}}
<x-ui.select model="filterStatus" placeholder="Semua Status"
    :options="[['value' => 'active', 'label' => 'Aktif'], ...]" />

{{-- Multiple (Eloquent collection) --}}
<x-ui.select model="selectedRoles" :options="$roles" :multiple="true" />

{{-- Custom key mapping --}}
<x-ui.select model="cat" :options="$categories" value-key="slug" label-key="title" />
```

**Penting**:
- `model` adalah **nama properti Livewire sebagai string** — komponen membaca/menulis via `$wire['<model>']` di Alpine. Untuk multiple, properti Livewire harus bertipe `array`; untuk single, `string`/`?int`.
- Options diterima sebagai: Eloquent collection (default key `id`/`name`), array `[['value','label'], ...]`, atau dengan `value-key`/`label-key` custom.
- Single → checkmark di kanan item; Multiple → checkbox + badge per pilihan + footer "Hapus semua".
- Search header pakai pola command-palette (icon + input borderless sebagai flex siblings, BUKAN absolute-positioned — hindari overlap).

---

## 🗄️ Database & Models

```
User ──┬── belongsTo Role (active role)
       ├── belongsToMany Role (via role_user, with is_default pivot)
       └── belongsTo Media (avatar)

Role ──┬── belongsToMany User (via role_user)
       └── belongsToMany Menu (via role_menu)

Menu ──┬── belongsTo Menu (parent)
       ├── hasMany Menu (children)
       └── belongsToMany Role (via role_menu)

News ──┬── belongsTo NewsCategory
       ├── belongsTo Media (image)
       ├── belongsTo User (created_by/updated_by)

Carousel ── belongsTo Media (image)
AboutUs  ── belongsTo Media (image/logo)
SystemSetting ── belongsTo Media (favicon)
Media ── standalone (uuid, original_filename, filename, size, mime_type)
Notification ── standalone (from_role_id, to_role_id, message, url, id_reference, read)
```

- Semua model punya `uuid`, `created_by`, `updated_by`, `deleted_at`
- `Media` model menggunakan tabel `medias` (custom table name)
- `News` model auto-generate slug dari title dan excerpt dari content
- `SystemSetting` menggunakan model-level caching (24 jam, auto-clear on save/delete)

---

## 🧩 Livewire Component Patterns

### Standard CRUD Component Structure

```php
class SomeIndex extends Component implements HasForms
{
    use InteractsWithForms, WithPagination, HasTableView;

    #[Url] public $search = '';
    #[Url] public $perPage = 10;
    public ?array $data = [];
    public ?Model $record = null;
    public $showModal = false;

    // boot() → inject repositories
    // mount() → fill form saat edit
    // form() → define Filament form schema
    // create() → reset + open modal
    // edit(Model) → fill form + open modal
    // save() → validate + create/update via repository
    // delete(Model) → delete via repository
    // closeModal() → reset state
    // render() → return view with paginated data
}
```

### Notification Pattern

```php
// Via WithNotifications concern (direkomendasikan):
use App\Livewire\Concerns\WithNotifications;

$this->attempt(
    fn() => $this->someRepo->create($this->data),
    'Data berhasil disimpan.',
    'Gagal menyimpan data.'
);

// Method: notifySuccess(), notifyError(), notifyWarning()
```

---

## 📧 Email & Notifications

- `VerifyEmailNotification` — Email verification dengan optional password inclusion (queued)
- `ResetPasswordQueued` — Password reset (queued)
- SMTP via Mailtrap (development)
- In-App Notifications via model `Notification` custom: `from_role_id`, `to_role_id`, `message`, `url`, `read`

### Email Templates (`views/emails/` + `components/emails/layout`)

- Layout email pakai brand biru `#3a6cf4` → `#2451d6` (selaras website). Bukan hijau lagi.
- **IP & Lokasi**: `VerifyEmailNotification` & `ResetPasswordQueued` menampilkan Alamat IP + Lokasi.
  - IP **HARUS** di-capture di `__construct()` via `request()->ip()` — karena notification di-queue, request context hilang saat job dieksekusi worker.
  - `resolveLocation()` dipanggil di `toMail()` (saat job jalan, boleh hit API eksternal `ip-api.com`, timeout 5s, silent fail). IP lokal/private → "Lokal / Development".
- **Logo email**: gunakan `url(\Illuminate\Support\Facades\Storage::url($logo))` — BUKAN `url('storage/' . $logo)`. Pastikan `APP_URL` benar agar logo bisa di-load email client.

---

## 🔒 Security

1. **`SecurityHeaders`** — X-Frame-Options, X-XSS-Protection, X-Content-Type-Options, Referrer-Policy, HSTS
2. **`CheckMenuAccess`** — RBAC middleware berdasarkan active role
3. Email verification required (`MustVerifyEmail`)
4. Bcrypt 12 rounds, session-based auth (database driver)
5. Rate limiting: auth 10 req/min, website 60 req/min

---

## 🚀 Development Commands

```bash
composer setup               # Setup project (install + migrate + seed)
composer dev                 # Concurrent: server + queue + logs + vite
composer test                # Run PHPUnit tests

php artisan serve            # Dev server
php artisan queue:listen     # Queue worker
php artisan pail --timeout=0 # Tail logs
npm run dev                  # Vite dev server (WAJIB jalan saat development)
npm run build                # Production build

php artisan migrate:fresh --seed  # Reset + seed database
```

---

## ⚠️ Gotchas & Important Notes

### 1. Livewire Boot vs Constructor
Repository injection **HARUS** di `boot()`, bukan `__construct()`.

### 2. Filament Forms State
Saat edit, convert ID ke string untuk Filament Select matching:
```php
$formData['roles'] = $user->roles->pluck('id')->map(fn ($id) => (string) $id)->toArray();
```

### 3. UUID Route Model Binding
Selalu gunakan `uuid` bukan integer `id` saat membuat route/link.

### 4. Menu System
Route yang terdaftar di tabel `menus` → restricted by RBAC. Route yang TIDAK terdaftar → accessible semua authenticated user.

### 5. Tailwind CSS v4
Pakai `@tailwindcss/vite` (bukan PostCSS). Gunakan class v4 canonical: `bg-linear-to-br` bukan `bg-gradient-to-br`, `shrink-0` bukan `flex-shrink-0`.

### 6. Media Upload Pattern
File upload via model `Media` sebagai intermediary. Model dengan file upload punya `media_id` FK ke tabel `medias`.

### 7. View Composer (Global Variables)
`AppServiceProvider` men-share `$settings` (SystemSetting) dan `$aboutUs` ke **SEMUA views** — tersedia di semua Blade template.

### 8. Queue Connection
Queue `database` driver. Pastikan `php artisan queue:listen` berjalan saat development.

### 9. website.js — Module Script
Vite menghasilkan `type="module"` script yang dieksekusi SETELAH `DOMContentLoaded`. Jangan membungkus kode di `website.js` dengan `document.addEventListener('DOMContentLoaded', ...)`, callback itu tidak akan pernah jalan. Saat ini `website.js` hanya menyalakan Alpine; logika interaksi ada inline di view.

### 10. Website CSS Terpisah dari Admin
`resources/css/website.css` dan `resources/js/website.js` adalah entry point **khusus public website**. Jangan import atau mix dengan `app.css`/`app.js` (mengandung Metronic/Flux/Filament/Highcharts yang tidak relevan untuk public).

### 11. Dropdown Bar Menu Admin — Jangan Clip
Bar menu admin (`partials/admin-topnav`) memakai dropdown `absolute` di bawah item. Jangan beri `overflow-hidden`/`overflow-x-auto` pada baris itu atau pada leluhurnya, dropdown akan terpotong. Baris ukur tersembunyi untuk "More" memakai `invisible` + `absolute`, bukan `display:none`, agar lebar item bisa diukur.

### 12. Warna Website ≠ Admin
Website publik dan auth: navy `#162948` + aksen emas `#E7BA4B`, biru brand `#2D68A7` (semuanya dari logo LSP, lihat `design.md`). Header admin memakai gradien biru logo yang sama.
Admin: header `#1a3e9a`, primary Metronic `#1b84ff` via `app.css`.
Keduanya tidak boleh dicampur.

### 13. Alpine `<template x-if>` Tidak Bisa Nested
Alpine TIDAK mendukung nested `<template x-if>`. Pecah jadi kondisi gabungan: `x-if="multiple && val.length > 0"`, `x-if="multiple && val.length === 0"`, dst. (lihat `components/ui/select.blade.php`). Untuk `x-show` di dropdown gunakan `style="display:none"` (bukan hanya `x-cloak`) agar tidak flash sebelum Alpine siap.

### 14. Capture IP Sebelum Queue
Notification/job yang butuh data request (IP, user-agent) **HARUS** menangkapnya di `__construct()` saat masih dalam HTTP context. Saat job dieksekusi queue worker, `request()` sudah kosong. Lihat `ResetPasswordQueued` & `VerifyEmailNotification`.

### 15. Filter Pattern di Index Component
Filter list (status, role, dll.) pakai pola: properti `filterX` (`#[Url]`, persist ke URL) + `pendingX` (state sementara di modal). `openFilterModal()` copy `filterX→pendingX`, `applyFilters()` copy balik `pendingX→filterX` lalu `resetPage()`. Repository sediakan method `searchWithFilters(...)`. Lihat `UserIndex`.

### 16. Filament Package Upgrade — Wajib `php artisan filament:assets`
Setelah `composer update` filament/*, JS statis di `public/js/filament/*` TIDAK ikut ter-update otomatis — jalankan `php artisan filament:assets`, kalau tidak form modal akan error di browser console (`filamentSchemaComponent is not defined`) meskipun PHP-side sudah benar. CSS tidak perlu di-republish: `@filamentStyles` v5 cuma emit CSS variables, styling komponen datang dari Tailwind `@source` scan ke `vendor/filament/**/*.blade.php` (lihat `resources/css/app.css`), bukan dari `public/css/filament/*.css` (file itu legacy/tidak dipakai lagi).

### 17. `settings/logs` — Local `php-fpm.log` Bisa Bikin Memory Exhausted
`config/log-viewer.php` meng-include path lokal `/opt/homebrew/var/log/php-fpm.log` (Homebrew). Kalau file itu tumbuh besar (ratusan MB), halaman `settings/logs` fatal error memory exhausted saat scan. Ini masalah environment lokal developer, bukan bug Laravel/opcodesio/log-viewer — rotate/hapus file tsb kalau kejadian.

### 18. CSS Dasar Wajib di `@layer` (website.css)
Aturan tanpa `@layer` mengalahkan SEMUA utilitas Tailwind v4. Aturan `h1 { color }` di luar layer membuat `text-white` tidak bekerja, dan `.btn { display: inline-flex }` di luar layer membuat `hidden` diabaikan. Taruh reset/heading di `@layer base` dan kelas buatan (`.btn`, `.btn-accent`, skala huruf) di `@layer components`.

### 19. Aturan Atribut Alpine (Blade)
- Jangan pakai tanda kutip ganda di dalam atribut `x-data`/`x-init`/`@click`, termasuk di komentar `//`. Itu menutup atribut lebih awal dan sisa kodenya bocor sebagai teks halaman.
- Ekspresi `x-init` tidak boleh diawali `try`. Bungkus: `(() => { try { ... } catch (e) { ... } })()`.
- `@js($data)` aman dipakai di atribut (kutipnya di-escape).

### 20. Flash Putih di Mode Gelap
Ada dua sumber, dan keduanya sudah ditangani di `partials/theme-scripts` (dimuat semua layout):
1. **Muat penuh:** setiap layout HARUS memuat `partials.fouc-prevention` paling awal di `<head>`. Tema yang hanya dipasang Alpine baru berlaku setelah halaman tergambar. Latar `html` juga mengikuti tema (`website.css` dan blok `<style>` di `app.blade.php`).
2. **`wire:navigate`:** Alpine menimpa atribut `class` pada `<html>` dengan milik halaman baru, sehingga `dark` hilang sesaat, lalu `app.css` (yang memberi `body, main, a, button, input...` transisi warna 0,2 detik) membuatnya memudar putih ke gelap. `MutationObserver` di `theme-scripts` memulihkan `dark` seketika dan menambahkan kelas `is-navigating` yang mematikan transisi pada perubahan gaya yang sama. Jangan hanya memulihkan kelas, dan jangan hanya membekukan transisi: keduanya perlu bersamaan. Diukur per frame: 0 frame terang dari 8 perpindahan antar menu admin.

### 21. Halaman Tidak Berubah Setelah Edit View/Kelas
Aset dipakai dari `public/build`. Jalankan `npm run build` (atau `npm run dev`) setelah menambah kelas Tailwind baru, dan `php artisan view:clear` bila Blade tampak tidak berubah.

---

## 📋 Checklist Menambahkan Fitur Baru

1. **Migration** — `uuid`, `created_by`, `updated_by`, `softDeletes`
2. **Model** — `HasUuid`, `SoftDeletes`, `$fillable`, relationships
3. **Repository Interface** — di `Repositories/Contracts/`
4. **Repository Implementation** — extend `BaseRepository`
5. **Binding** — daftarkan di `RepositoryServiceProvider::$bindings`
6. **Form** — Filament Form schema di `Forms/`
7. **Livewire Component** — inject repository via `boot()`
8. **Blade View** — di `resources/views/livewire/`
9. **Route** — di module yang sesuai (`routes/modules/`)
10. **Menu Seeder** — update `MenuSeeder`
11. **Test** — tulis test jika diperlukan

---

## 🧪 Testing

- Framework: PHPUnit 13
- Test directory: `tests/`
- Run: `php artisan test` atau `composer test`

---

## 📦 Key Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | ^13.0 | Core framework |
| `livewire/livewire` | ^4.0 | Full-page reactive components |
| `livewire/flux` | ^2.21 | Premium Livewire UI components (admin) |
| `filament/forms` | ^5.0 | Form builder (schema-only, pakai `filament/schemas` internal) |
| `opcodesio/log-viewer` | ^3.24 | Log viewer UI (super-admin only) |
| `tailwindcss` | ^4.3.3 | Utility-first CSS framework |
| `vite` | ^8.3.5 | Frontend build tool |
| `highcharts` | ^13.1.1 (npm) | Dashboard charts (admin only) |
| `alpinejs` | ^3.17 (npm) | Reactive JS — di-bundle via website.js |
| `@alpinejs/collapse` | ^3.17 (npm) | Alpine collapse plugin (FAQ/accordion) |

> **Flowbite** digunakan via CDN untuk admin layout (header). Tidak di-bundle via npm.

### Dev Dependencies

| Package | Purpose |
|---------|---------|
| `laravel/pail` | Real-time log tailing |
| `laravel/pint` | Code style fixer (PSR-12) |
| `laravel/sail` | Docker environment |
| `mockery/mockery` | Mocking library |
| `phpunit/phpunit` | Testing framework |
| `concurrently` | Run multiple commands simultaneously |
