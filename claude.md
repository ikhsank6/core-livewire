# CLAUDE.md — Project Intelligence

> File ini adalah sumber utama konteks bagi AI assistant (Claude, Gemini, dll.) ketika bekerja dengan codebase ini.
> Ditulis dari perspektif **Senior Fullstack Engineer** yang memahami setiap lapisan arsitektur.
> **Versi dokumen**: v2.3.1

---

## 🏗️ Project Overview

**Nama**: Laravel Livewire CMS Platform
**Versi**: 2.3.1
**Stack**: Laravel 12 + Livewire 3 + Tailwind CSS 4 + Vite 7 + Alpine.js 3
**Database**: SQLite (development), bisa di-swap ke MySQL/PostgreSQL
**PHP**: >= 8.2
**Tipe Aplikasi**: Content Management System (CMS) dengan role-based access control (RBAC), public-facing website (Aveit-inspired design), dan admin panel yang dibangun sepenuhnya dengan Livewire full-page components.

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
│   ├── Layout/        # NotificationBell, NotificationIndex, Sidebar
│   ├── Menus/         # MenuIndex, RoleMenuAccess
│   ├── News/          # NewsIndex
│   ├── NewsCategories/# NewsCategoryIndex
│   ├── Roles/         # RoleIndex
│   ├── Settings/      # JobIndex, LogIndex, SystemSettingIndex
│   ├── Users/         # UserIndex
│   └── Dashboard.php
├── Models/            # Eloquent models (10 models)
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
│   └── website.css    # Public website styles (Aveit design system, terpisah dari admin)
├── js/
│   ├── app.js         # Admin panel JS (Highcharts, Flowbite init)
│   └── website.js     # Public website JS (Alpine.js bundle + AOS + counter)
└── views/
    ├── components/
    │   ├── layouts/   # app.blade.php (admin), auth.blade.php (split-screen auth)
    │   ├── ui/        # Reusable UI components (modal, table, card, badge, pagination, etc.)
    │   └── emails/    # Email templates
    ├── livewire/      # Blade views untuk setiap Livewire component
    ├── layouts/       # website.blade.php (public website layout)
    ├── website/       # Public website pages (home, about, news)
    │   └── partials/  # section-heading, news-card, nav, footer, page-header, logo, dll.
    └── partials/      # Shared partials (meta, favicon, fonts, alpine-cloak, dll.)

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

### 4. Actions Pattern

Single-responsibility invokable classes untuk operasi yang bukan CRUD:
- **Website Actions** (`Actions/Website/`): ShowHomePage, ShowNewsList, ShowNewsDetail, ShowAboutPage
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

## 🌐 Public Website (v2.2.x — Redesign)

### Design System

Website public menggunakan design system **Aveit-inspired** yang **sepenuhnya terpisah** dari admin:

| Token | Value | Catatan |
|-------|-------|---------|
| `--color-primary` | `#3a6cf4` | Blue vibrant (berbeda dari admin) |
| `--color-primary-dark` | `#2451d6` | |
| `--color-accent` | `#f97316` | Orange CTA |
| `--color-dark` | `#0d1117` | Dark navy background |
| `--color-dark-card` | `#161b25` | |
| `--font-heading` | Quicksand | Google Fonts |
| `--font-sans` | Nunito + Inter | Google Fonts |

### Build Pipeline (Vite Entry Points)

```js
// vite.config.js — 4 entry points
input: [
    'resources/css/app.css',    // Admin — Metronic/Flux/Filament
    'resources/js/app.js',      // Admin — Highcharts, Flowbite re-init
    'resources/css/website.css',// Website — Aveit design system
    'resources/js/website.js',  // Website — Alpine.js bundle + AOS
]
```

**PENTING**: Jangan mix kedua CSS. `app.css` mengandung Flux/Filament/Metronic styles yang tidak relevan untuk public website.

### Alpine.js di Website

Alpine.js di-bundle via npm di `website.js` (bukan CDN):

```js
// resources/js/website.js
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();
// AOS & counter — run langsung (JANGAN bungkus DOMContentLoaded, lihat Gotcha #9)
initAos();
initCounters();
```

### AOS (Animate on Scroll)

Custom IntersectionObserver di `website.js`:
- Class `aos-init` → `opacity: 0` + `transform`
- Class `aos-left` / `aos-right` / `aos-scale` untuk arah animasi
- Observer menambahkan `aos-animated` saat elemen masuk viewport
- Safety timeout 500ms: reveal semua elemen yang belum animated

### Route Structure (rate limited: 60 req/min)

| Route | Action | Description |
|-------|--------|-------------|
| `GET /` | `ShowHomePage` | Landing page (carousel + about snippet + news) |
| `GET /news` | `ShowNewsList` | News listing dengan pagination & sidebar |
| `GET /news/{slug}` | `ShowNewsDetail` | News detail + related news |
| `GET /about` | `ShowAboutPage` | About us + kontak + Google Maps |

### Reusable Website Partials

| Partial | Props | Keterangan |
|---------|-------|-----------|
| `partials/section-heading` | `eyebrow`, `title`, `subtitle`, `align` | Section header reusable |
| `partials/news-card` | `item` (News), `featured` (bool) | Kartu berita (standard & featured) |
| `partials/page-header` | `title`, `breadcrumb`, `subtitle`, `bgImage` | Dark hero header halaman inner |
| `partials/nav` | — | Sticky glass navbar + spring hover animations |
| `partials/footer` | — | 4-column footer, data dari `$aboutUs` |

---

## 🎨 Frontend Stack

### Admin Panel

- **Tailwind CSS 4** via `@tailwindcss/vite` plugin
- **Flowbite** (CDN) — sidebar, header, responsive shell, Alpine.js interactivity
- **Highcharts** (npm, ^12.6.0) — Dashboard donut/bar chart, exposed via `window.Highcharts`
- **Livewire Flux** (v2.10) — Premium UI components (breadcrumbs, badges, form inputs)
- **Filament Forms** — Form schema only (bukan Filament panel)

### Auth Pages

- Layout: **Split-screen** — Left panel (dark branding + floating CMS mockup), Right panel (form)
- Font: Quicksand (heading) + Nunito (body) via Google Fonts
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

### 9. website.js — Module Script (PENTING)
Vite menghasilkan `type="module"` script. Module scripts dieksekusi setelah DOM ready tapi SETELAH `DOMContentLoaded` sudah fired. Akibatnya, `document.addEventListener('DOMContentLoaded', callback)` di dalam `website.js` **tidak akan pernah terpanggil**. Selalu run kode langsung (tanpa wrapper DOMContentLoaded):
```js
// ✅ Benar
initAos();
initCounters();

// ❌ Salah — callback tidak akan jalan
document.addEventListener('DOMContentLoaded', () => { initAos(); });
```

### 10. Website CSS Terpisah dari Admin
`resources/css/website.css` dan `resources/js/website.js` adalah entry point **khusus public website**. Jangan import atau mix dengan `app.css`/`app.js` (mengandung Metronic/Flux/Filament/Highcharts yang tidak relevan untuk public).

### 11. Sidebar Tooltip — position: fixed
Tooltip sidebar admin menggunakan `position: fixed` dengan koordinat Alpine `@mouseenter`. JANGAN ganti ke `position: absolute` — akan di-clip oleh `overflow-y: auto` pada elemen nav parent (CSS spec: overflow-y:auto memaksa overflow-x minimal auto, sehingga clip absolute descendants).

### 12. Warna Primary Website ≠ Admin
Website public: `--color-primary: #3a6cf4` (blue)
Admin panel: menggunakan Metronic primary `#1b84ff` via `app.css`
Keduanya tidak boleh dicampur.

### 13. Alpine `<template x-if>` Tidak Bisa Nested
Alpine TIDAK mendukung nested `<template x-if>`. Pecah jadi kondisi gabungan: `x-if="multiple && val.length > 0"`, `x-if="multiple && val.length === 0"`, dst. (lihat `components/ui/select.blade.php`). Untuk `x-show` di dropdown gunakan `style="display:none"` (bukan hanya `x-cloak`) agar tidak flash sebelum Alpine siap.

### 14. Capture IP Sebelum Queue
Notification/job yang butuh data request (IP, user-agent) **HARUS** menangkapnya di `__construct()` saat masih dalam HTTP context. Saat job dieksekusi queue worker, `request()` sudah kosong. Lihat `ResetPasswordQueued` & `VerifyEmailNotification`.

### 15. Filter Pattern di Index Component
Filter list (status, role, dll.) pakai pola: properti `filterX` (`#[Url]`, persist ke URL) + `pendingX` (state sementara di modal). `openFilterModal()` copy `filterX→pendingX`, `applyFilters()` copy balik `pendingX→filterX` lalu `resetPage()`. Repository sediakan method `searchWithFilters(...)`. Lihat `UserIndex`.

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

- Framework: PHPUnit 11
- Test directory: `tests/`
- Run: `php artisan test` atau `composer test`

---

## 📦 Key Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | ^12.0 | Core framework |
| `livewire/livewire` | ^3.0 | Full-page reactive components |
| `livewire/flux` | ^2.10 | Premium Livewire UI components (admin) |
| `filament/forms` | ^3.0 | Form builder (schema-only) |
| `opcodesio/log-viewer` | ^3.21 | Log viewer UI (super-admin only) |
| `tailwindcss` | ^4.0.0 | Utility-first CSS framework |
| `vite` | ^7.0.7 | Frontend build tool |
| `highcharts` | ^12.6.0 (npm) | Dashboard charts (admin only) |
| `alpinejs` | ^3.15 (npm) | Reactive JS — di-bundle via website.js |
| `@alpinejs/collapse` | ^3.15 (npm) | Alpine collapse plugin (FAQ/accordion) |

> **Flowbite** digunakan via CDN untuk admin layout (sidebar, header). Tidak di-bundle via npm.

### Dev Dependencies

| Package | Purpose |
|---------|---------|
| `laravel/pail` | Real-time log tailing |
| `laravel/pint` | Code style fixer (PSR-12) |
| `laravel/sail` | Docker environment |
| `mockery/mockery` | Mocking library |
| `phpunit/phpunit` | Testing framework |
| `concurrently` | Run multiple commands simultaneously |
