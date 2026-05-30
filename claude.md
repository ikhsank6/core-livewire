# CLAUDE.md — Project Intelligence

> File ini adalah sumber utama konteks bagi AI assistant (Claude, Gemini, dll.) ketika bekerja dengan codebase ini.
> Ditulis dari perspektif **Senior Fullstack Engineer** yang memahami setiap lapisan arsitektur.

---

## 🏗️ Project Overview

**Nama**: Laravel Livewire CMS Platform
**Stack**: Laravel 12 + Livewire 3 + Flux UI + Filament Forms + Tailwind CSS 4 + Vite 7
**Database**: SQLite (development), bisa di-swap ke MySQL/PostgreSQL
**PHP**: >= 8.2
**Tipe Aplikasi**: Content Management System (CMS) dengan role-based access control (RBAC), public-facing website, dan admin panel yang dibangun sepenuhnya dengan Livewire full-page components.

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
│   └── Middleware/     # CheckMenuAccess, SecurityHeaders
├── Livewire/          # Livewire full-page components (pengganti Controller tradisional)
│   ├── Auth/          # Login, Register, ForgotPassword, ResetPassword, VerifyEmail, Profile, ChangePassword
│   ├── AboutUs/       # AboutUsIndex
│   ├── Carousels/     # CarouselIndex
│   ├── Concerns/      # Shared traits (HasTableView, WithPasswordValidation)
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

resources/views/
├── components/
│   ├── layouts/       # app.blade.php (admin), auth.blade.php (auth pages)
│   ├── ui/            # Reusable UI components (modal, table, card, badge, pagination, etc.)
│   └── emails/        # Email templates
├── livewire/          # Blade views untuk setiap Livewire component
├── layouts/           # website.blade.php (public website layout)
├── website/           # Public website pages (home, about, news)
└── partials/          # Shared partials

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

**Contoh flow**:
- `UserRepositoryInterface` → `UserRepository extends BaseRepository` → Di-bind di `RepositoryServiceProvider` → Di-inject via `boot()` method di Livewire component.

**BaseRepository** menyediakan method standar:
- `all()`, `paginate()`, `find()`, `findByUuid()`, `findByUuidOrFail()`, `findOrFail()`
- `create()`, `update()`, `delete()` — semua dibungkus `DB::transaction()`
- `search()` — generic search dengan multiple searchable columns

**Rules**:
- Jangan pernah panggil `Model::query()` langsung di Livewire component
- Selalu buat interface di `Contracts/` terlebih dahulu
- Daftarkan binding di `RepositoryServiceProvider::$bindings`
- Inject repository via Livewire `boot()` method (bukan constructor)

### 2. Livewire Full-Page Components

Project ini **TIDAK menggunakan Laravel Controllers tradisional** untuk admin panel. Semua halaman admin adalah Livewire full-page components.

**Conventions**:
```php
#[Layout('components.layouts.app')]
#[Title('Page Title')]
class SomePage extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    // Repository injection via boot(), BUKAN constructor
    public function boot(SomeRepositoryInterface $repo): void
    {
        $this->someRepo = $repo;
    }
}
```

**Penting**:
- Gunakan `#[Layout('components.layouts.app')]` untuk admin pages
- Gunakan `#[Layout('components.layouts.auth')]` untuk auth pages
- Repository di-inject via `boot()` method karena Livewire component di-recreate setiap request
- Properties yang di-persist ke URL gunakan `#[Url]`

### 3. Filament Forms Integration

Form schema didefinisikan sebagai **static method** di class terpisah di `app/Forms/`:

```php
class UserForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('name')->required(),
            // ...
        ];
    }
}
```

Kemudian digunakan di Livewire component:
```php
public function form(Form $form): Form
{
    return $form
        ->schema(UserForm::schema())
        ->statePath('data')
        ->model($this->record ?? User::class);
}
```

**Penting**: Filament Forms hanya digunakan untuk **form schema**, BUKAN Filament panel/admin. Ini bukan project Filament Admin Panel.

### 4. Actions Pattern

Single-responsibility invokable classes untuk operasi yang bukan CRUD:

```php
class LogoutAction
{
    public function __invoke(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('auth.login');
    }
}
```

- **Website Actions** (`Actions/Website/`): Public page controllers (ShowHomePage, ShowNewsList, dll.)
- **Auth Actions**: LogoutAction, SwitchRoleAction

### 5. UUID & Audit Trail (HasUuid Trait)

Semua model utama menggunakan `HasUuid` trait yang:
- Auto-generate UUID saat `creating`
- Auto-set `created_by` dan `updated_by` dengan nama user yang login
- Menggunakan `uuid` sebagai route key name (bukan `id`)
- Menyediakan `findByUuid()` dan `findByUuidOrFail()`

**Penting**: Saat membuat URL atau route model binding, selalu gunakan `uuid`, bukan `id`.

### 6. Soft Deletes

Semua model utama menggunakan `SoftDeletes`. Pastikan:
- Selalu gunakan `SoftDeletes` trait di model baru
- Migration harus include `$table->softDeletes()`

---

## 🔐 Authorization System (RBAC)

### Hierarchy

```
User → has many Roles (via role_user pivot)
User → has one active Role (role_id foreign key)
Role → has many Menus (via role_menu pivot)
Menu → has parent/children (self-referencing, nested tree)
```

### How It Works

1. **Login**: User di-set active role ke default role (`is_default` di pivot `role_user`)
2. **Middleware `menu.access`**: Cek apakah route yang diakses ada di tabel `menus`. Jika ada, cek apakah role aktif user punya akses ke menu tersebut.
3. **Route yang TIDAK ada di tabel menus**: Dianggap "general route" yang bisa diakses semua authenticated user (misal: profile, change password).
4. **Switch Role**: User bisa switch active role via `SwitchRoleAction`. Menu cache di-clear saat switch.

### Gates

```php
Gate::define('access-route', ...)   // Cek akses route via MenuService
Gate::define('access-menu', ...)    // Cek akses menu spesifik
Gate::define('viewLogViewer', ...)  // Hanya super-admin
```

### Menu Caching

`MenuService` meng-cache menu tree per role (`menu_tree_role_{id}`) selama 1 jam. Cache di-clear saat:
- Role menu access diubah
- User switch role

---

## 🌐 Public Website

### Route Structure (rate limited: 60 req/min)

| Route | Action | Description |
|-------|--------|-------------|
| `GET /` | `ShowHomePage` | Landing page |
| `GET /news` | `ShowNewsList` | News listing dengan pagination |
| `GET /news/{slug}` | `ShowNewsDetail` | News detail by slug |
| `GET /about` | `ShowAboutPage` | About us page |

### Layout

Public website menggunakan layout `layouts/website.blade.php`, berbeda dari admin layout.

### Rate Limiting

- `website`: 60 req/min per IP (browsing)
- `website-forms`: 10 req/min per IP (form submissions)
- `website-api`: 30 req/min per IP (API-like endpoints)

---

## 🗄️ Database & Models

### Entity Relationship

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
       ├── belongsTo User (created_by)
       └── belongsTo User (updated_by)

Carousel ── belongsTo Media (image)
AboutUs ── belongsTo Media (image)
SystemSetting ── belongsTo Media (favicon)

Media ── standalone (uuid, original_filename, filename, size, mime_type)
Notification ── standalone (from_role_id, to_role_id, message, url, id_reference, read)
```

### Model Conventions

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

    // boot() untuk inject repositories
    // mount() untuk fill form
    // form() untuk define Filament form
    // create() → reset + open modal
    // edit(Model $model) → fill form + open modal
    // save() → validate + create/update via repository
    // delete(Model $model) → delete via repository
    // closeModal() → reset state
    // render() → return view with paginated data
}
```

### Notification Pattern (Livewire Events)

```php
// Dispatch notification event
$this->dispatch('notify', text: 'Success message', variant: 'success');
$this->dispatch('notify', text: 'Error message', variant: 'danger');
```

### Modal Pattern

Modal state dikelola via `$showModal` property. Form di-reset saat modal ditutup via `closeModal()` dan `updatedShowModal()`.

---

## 🎨 Frontend Stack

### Tailwind CSS 4

- Menggunakan `@tailwindcss/vite` plugin (bukan PostCSS)
- Filament preset di-include via `tailwind.config.js`
- Dark mode menggunakan `selector` strategy

### Livewire Flux

Package `livewire/flux` (v2.10) menyediakan komponen UI premium untuk Livewire.

### Custom UI Components (`views/components/ui/`)

- `modal.blade.php` — Reusable modal dialog
- `table/` — Table components
- `card.blade.php` — Card component
- `badge.blade.php` — Badge component
- `pagination.blade.php` — Custom pagination
- `delete-confirm-modal.blade.php` — Delete confirmation modal
- `empty-state.blade.php` — Empty state placeholder
- `avatar.blade.php` — User avatar component
- `button/` — Button components
- `password-strength.blade.php` — Password strength indicator

### Vite Configuration

```js
// Entry points
resources/css/app.css
resources/js/app.js

// Features
- Hot reload enabled (refresh: true)
- Ignores storage/framework/views for watch performance
```

---

## 📧 Email & Notifications

### Queued Notifications

- `VerifyEmailNotification` — Email verification dengan optional password inclusion
- `ResetPasswordQueued` — Password reset (queued)

### Mail Config

SMTP via Mailtrap (development). Konfigurasi di `.env`.

### In-App Notifications

Model `Notification` custom (bukan Laravel notifications table):
- `from_role_id`, `to_role_id` — Role-based notification routing
- `message`, `url`, `id_reference`, `read`
- Ditampilkan via `NotificationBell` dan `NotificationIndex` Livewire components

---

## 🔒 Security

### Middleware

1. **`SecurityHeaders`** — Menambahkan security headers (X-Frame-Options, X-XSS-Protection, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, HSTS)
2. **`CheckMenuAccess`** — RBAC middleware yang mengecek akses menu berdasarkan active role

### Authentication

- Email verification required (`MustVerifyEmail` interface)
- Bcrypt dengan 12 rounds
- Session-based auth (database driver)
- Rate limiting pada auth routes (10 req/min)

### Other

- CSRF protection (Laravel default)
- Mass assignment protection via `$fillable`
- Password hashing via `'password' => 'hashed'` cast

---

## 🚀 Development Commands

```bash
# Setup project
composer setup

# Development (concurrent: server + queue + logs + vite)
composer dev

# Run tests
composer test

# Individual commands
php artisan serve              # Start dev server
php artisan queue:listen       # Start queue worker
php artisan pail --timeout=0   # Tail logs
npm run dev                    # Vite dev server

# Database
php artisan migrate            # Run migrations
php artisan db:seed            # Run seeders
php artisan migrate:fresh --seed  # Fresh migration + seed
```

---

## ⚠️ Gotchas & Important Notes

### 1. Livewire Boot vs Constructor
Repository injection **HARUS** di `boot()`, bukan `__construct()`. Livewire component di-hydrate ulang setiap request, jadi constructor-based injection tidak reliable.

### 2. Filament Forms State
Form data disimpan di `$data` array property dengan `->statePath('data')`. Saat edit, harus convert ID ke string untuk Filament Select matching:
```php
$formData['roles'] = $user->roles->pluck('id')->map(fn ($id) => (string) $id)->toArray();
```

### 3. UUID Route Model Binding
Model menggunakan `uuid` sebagai route key name. Saat membuat route atau link, pastikan passing UUID bukan integer ID.

### 4. Menu System
- Route yang TIDAK terdaftar di tabel `menus` → accessible oleh semua authenticated users
- Route yang terdaftar di tabel `menus` → dicek via role-based access
- Ini berarti menambahkan route baru ke `menus` table akan membuatnya restricted

### 5. Tailwind CSS v4
Project ini menggunakan Tailwind CSS v4 dengan `@tailwindcss/vite` plugin. Jangan gunakan syntax Tailwind v3 (PostCSS-based). Configuration file `tailwind.config.js` masih diperlukan untuk Filament preset compatibility.

### 6. Media Upload Pattern
File upload menggunakan model `Media` sebagai intermediary. Model yang punya file upload (User avatar, News image, Carousel, dll.) memiliki `media_id` foreign key yang me-reference ke tabel `medias`.

### 7. View Composer (Global Variables)
`AppServiceProvider` mendaftarkan view composer yang men-share `$settings` (SystemSetting) dan `$aboutUs` ke **SEMUA views**. Variabel ini tersedia di semua Blade template.

### 8. Queue Connection
Queue menggunakan `database` driver. Pastikan `php artisan queue:listen` berjalan saat development untuk memproses jobs (email, dll.).

---

## 📋 Checklist untuk Menambahkan Fitur Baru

Saat menambahkan module/fitur CRUD baru, ikuti langkah berikut:

1. **Migration** — Buat migration dengan `uuid`, `created_by`, `updated_by`, `softDeletes`
2. **Model** — Buat model dengan `HasUuid`, `SoftDeletes` traits, definisikan `$fillable` dan relationships
3. **Repository Interface** — Buat interface di `Repositories/Contracts/`
4. **Repository Implementation** — Extend `BaseRepository`, implement interface
5. **Binding** — Daftarkan di `RepositoryServiceProvider::$bindings`
6. **Form** — Buat Filament Form schema di `Forms/`
7. **Livewire Component** — Buat full-page component, inject repository via `boot()`
8. **Blade View** — Buat view di `resources/views/livewire/`
9. **Route** — Tambahkan route di module yang sesuai (`routes/modules/`)
10. **Menu Seeder** — Update `MenuSeeder` untuk menambahkan menu baru
11. **Test** — Tulis test jika diperlukan

---

## 🧪 Testing

- Framework: PHPUnit 11
- Test directory: `tests/`
- Run: `php artisan test` atau `composer test`
- Config clears before test run (via composer script)

---

## 📦 Key Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | ^12.0 | Core framework |
| `livewire/livewire` | ^3.0 | Full-page reactive components |
| `livewire/flux` | ^2.10 | Premium Livewire UI components |
| `filament/forms` | ^3.0 | Form builder (schema-only, bukan panel) |
| `opcodesio/log-viewer` | ^3.21 | Log viewer UI (restricted to super-admin) |
| `tailwindcss` | ^4.0.0 | Utility-first CSS framework |
| `vite` | ^7.0.7 | Frontend build tool |

### Dev Dependencies

| Package | Purpose |
|---------|---------|
| `laravel/pail` | Real-time log tailing |
| `laravel/pint` | Code style fixer (PSR-12) |
| `laravel/sail` | Docker environment |
| `mockery/mockery` | Mocking library |
| `phpunit/phpunit` | Testing framework |
| `concurrently` | Run multiple commands simultaneously |
