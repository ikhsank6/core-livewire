# Laravel Livewire CMS Platform

> **v2.3.1** — Comprehensive Laravel 12 + Livewire 3 CMS with role-based access control, a reusable autocomplete select component, advanced user filtering, bulk email verification, and IP/location-aware email notifications.

## 🚀 Tech Stack

| Category | Technology |
|---|---|
| **Framework** | Laravel 12 |
| **Reactive UI** | Livewire 3 |
| **CSS Framework** | Tailwind CSS 4 (Vite plugin) |
| **JavaScript** | Alpine.js 3 (npm bundle) |
| **Admin UI** | Flux UI + Filament Forms + Flowbite (CDN) |
| **Charts** | Highcharts 12 |
| **Database** | SQLite (configurable → MySQL / PostgreSQL) |
| **Queue** | Database driver |
| **Email** | SMTP with queued notifications |
| **Build Tool** | Vite 7 (dual entry: admin + website) |

---

## ✨ Features

### 🔐 Authentication & Security

- Full auth flow: Login, Register, Forgot Password, Reset Password, Email Verification
- **Split-screen auth layout** — Dark branded left panel (floating CMS mockup) + form right panel
- Theme toggle with sun/moon icons inside the toggle button
- Queued email notifications (Mailtrap-ready)
- Standardized password validation (NIST/OWASP) with real-time strength indicator
- Rate limiting: auth routes 10 req/min, public website 60 req/min
- Role-based access control (RBAC)

### 🌐 Public Website (v2.2.x Redesign)

A fully redesigned public-facing website with a dedicated Aveit-inspired design system:

- **Hero Carousel** — Pure Alpine.js (no Swiper), autoplay, dot navigation, keyboard-friendly
- **Home Page** — CMS-driven: carousel → about snippet → latest news → CTA. Zero hardcoded placeholder content.
- **News Section** — Featured article (horizontal card), article grid, categories sidebar, related news
- **About Page** — Company description, contact details, Google Maps embed
- **Animated Navigation** — Sticky glass navbar with spring-based hover pill, animated active underline bar, shimmer sweep on hover
- **Dark/Light Mode** — System-aware default, persisted to localStorage, smooth transition
- **AOS (Animate on Scroll)** — Custom IntersectionObserver implementation (no library dependency)
- **SEO** — Meta tags, Open Graph, Twitter Cards
- **Responsive** — Mobile-first, tested 360px → 1440px

**Website design tokens** (separate from admin):
- Primary: `#3a6cf4` (blue), Accent: `#f97316` (orange), Dark: `#0d1117` (navy)
- Fonts: Quicksand (headings) + Nunito (body) via Google Fonts

### 📰 CMS (Content Management)

- **News** — Create/edit/publish articles, rich text editor, auto-slug, excerpt generation
- **News Categories** — Organize articles, filter by category on public website
- **Carousels** — Homepage slider management, drag-and-drop ordering
- **About Us** — Company profile, contact info, social links, map coordinates, logo

### ⚙️ Settings

- **System Settings** — Favicon, SEO metadata, Google Analytics
- **Log Viewer** — View/filter/search Laravel logs (super-admin only, Opcodes Log Viewer)
- **Job Monitor** — View queued jobs

### 👥 Master Data

- **User Management** — CRUD, auto-generated passwords, avatar, role assignment
- **Role Management** — Create roles, assign menu access per role
- **Menu Management** — Dynamic menu builder, drag-and-drop ordering, Heroicon support

### 📊 Dashboard

- Interactive Highcharts donut/bar chart (user distribution)
- Key metrics cards
- Re-initializes after Livewire SPA navigation

### 🎨 Admin UI/UX

- Collapsible sidebar with **fixed-position tooltips** (no overflow clipping)
- Dark mode throughout admin panel
- Toast notifications (success, danger, warning, info)
- Reusable components: modals, tables, cards, badges, pagination, empty states, **autocomplete select** (`<x-ui.select>` — single/multiple + searchable)
- Real-time password strength indicator
- Filter modals with URL-persisted state and active-filter badge counters

### 🔔 In-App Notifications

- Custom notification model (role-based routing: `from_role_id`, `to_role_id`)
- Notification bell with unread count
- Notification inbox with read/unread filters

---

## 📦 Installation

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+ & NPM
- SQLite (or MySQL/PostgreSQL)

### Steps

```bash
# 1. Clone & install
git clone <repo>
cd <repo>
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan storage:link

# 3. Configure .env
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@example.com

# 4. Database
php artisan migrate:fresh --seed

# 5. Build assets
npm run build      # production
# OR
npm run dev        # development (HMR)
```

### Default Credentials

| Role | Email | Password |
|---|---|---|
| Super Admin | `superadmin@example.com` | `password` |

### Run (Development)

```bash
# All-in-one (recommended)
composer dev

# Or individually:
php artisan serve          # Terminal 1
npm run dev                # Terminal 2
php artisan queue:listen   # Terminal 3
php artisan pail           # Terminal 4 (logs)
```

---

## 📁 Project Structure

```
app/
├── Actions/
│   └── Website/           # Public page action classes (ShowHomePage, ShowNewsList, ...)
├── Forms/                 # Filament form schemas
├── Http/Middleware/       # CheckMenuAccess, SecurityHeaders
├── Livewire/
│   ├── Auth/              # Authentication components
│   ├── Concerns/          # Shared traits (WithNotifications, WithRateLimiting, ...)
│   ├── Layout/            # Sidebar, NotificationBell, NotificationIndex
│   ├── Settings/          # System settings, Logs, Jobs
│   └── [Feature]/         # Feature-specific CRUD components
├── Models/                # 10 Eloquent models (all with UUID + soft deletes)
├── Repositories/          # Repository pattern (Interface → Implementation)
└── Services/MenuService.php

resources/
├── css/
│   ├── app.css            # Admin styles (Metronic/Flux/Filament)
│   └── website.css        # Website styles (Aveit design system)
├── js/
│   ├── app.js             # Admin JS (Highcharts, Flowbite)
│   └── website.js         # Website JS (Alpine.js + AOS + counter)
└── views/
    ├── components/
    │   ├── layouts/       # app.blade.php (admin), auth.blade.php (split-screen)
    │   └── ui/            # Reusable UI components
    ├── livewire/          # Livewire blade views
    ├── layouts/           # website.blade.php
    └── website/           # Public pages + partials

routes/modules/
├── auth.php               # Authentication routes
├── cms.php                # CMS routes
├── master_data.php        # User/Role/Menu routes
├── settings.php           # Settings routes
└── website.php            # Public routes (rate limited)
```

---

## 🛠️ Key Patterns

### Adding a New CMS Module

1. Migration → Model (`HasUuid`, `SoftDeletes`) → Repository Interface → Implementation → Binding
2. Form schema in `app/Forms/` → Livewire component (inject repo via `boot()`) → Blade view
3. Route in `routes/modules/` → Menu seeder entry

### Protecting Admin Routes

```php
Route::middleware(['auth', 'verified', 'menu.access'])->group(function () {
    Route::get('/your-route', YourComponent::class)->name('your.route');
});
```

### Adding Website Partials

Website partials live in `resources/views/website/partials/`. Key reusable ones:

```blade
{{-- Section heading --}}
@include('website.partials.section-heading', [
    'eyebrow' => 'Label',
    'title'   => 'Judul <span class="text-primary">Halaman</span>',
    'subtitle' => 'Deskripsi singkat.',
])

{{-- News card (standard or featured) --}}
@include('website.partials.news-card', ['item' => $newsItem, 'featured' => true])

{{-- Page header (inner pages) --}}
@include('website.partials.page-header', [
    'title'      => 'Judul Halaman',
    'breadcrumb' => 'Breadcrumb Label',
])
```

---

## 🔧 Artisan Commands

```bash
php artisan optimize:clear     # Clear all caches
php artisan queue:work         # Run queue worker
php artisan queue:retry all    # Retry failed jobs
php artisan migrate:fresh --seed  # Reset database
```

---

## 📋 Changelog

### v2.3.1
- **Global Autocomplete Select** (`<x-ui.select>`): Komponen select reusable berbasis Tailwind + Alpine.js — mendukung single & multiple, search/autocomplete bawaan, clearable (per-item & "Hapus semua"), checkbox untuk multiple / checkmark untuk single, dan dark mode. Terima Eloquent collection, array `['value','label']`, atau custom `value-key`/`label-key`.
- **User Filtering**: Filter pengguna via modal — Status (single) & Role (multiple), URL-persisted, badge counter jumlah filter aktif di tombol Filter. Tombol Filter ditempatkan di sebelah kanan input search (slot `searchAction` baru di table header).
- **Bulk Resend Verification Email**: Checkbox per-baris (hanya user belum terverifikasi) + select-all, dengan toolbar aksi "Kirim Verifikasi" yang muncul kontekstual saat ada pilihan.
- **Email Notifications — IP & Lokasi**: Email reset password & verifikasi kini menampilkan Alamat IP + Lokasi (geo-lookup via `ip-api.com`, IP lokal ditandai "Lokal / Development"). IP di-capture di constructor (sebelum job di-queue).
- **Email Branding**: Warna email diselaraskan ke brand biru (`#3a6cf4`); fix logo email tidak load (`url(Storage::url($logo))`).
- **Profile & Change Password Layout**: Profile cards dibuat berdampingan (Personal Info 3/5 + Roles 2/5); Change Password mengikuti lebar penuh layout admin.

### v2.3.0
- **Public Website Button & Color Harmonization**: Unified button styling across public views (navbar, hero, and CTA sections) using brand primary blue (`#3a6cf4`) instead of orange clashing colors.
- **Submit Button Icons**: Added action icons (sign-in, user-add, mail) to login, register, and forgot password submit buttons.
- **Daily logging configuration**: Switched default logging to `'daily'` channel with auto-rotating logs.
- **Admin User Table Checkbox**: Retained checkboxes for active/verified users in User list table but styled them as disabled and cursor-not-allowed.
- **Global CSS Cleanup**: Centralized `.eyebrow-pill-light` styles into `website.css` and removed local style overrides.

### v2.2.1
- Fix: sidebar tooltips tidak muncul (overflow-x clipping) → tooltip pakai `position: fixed` dengan Alpine coordinate tracking
- Fix: navigation menu spring-based hover & active animations (`nav-pill`, `nav-active-bar`, `nav-shimmer`)
- Fix: auth layout theme toggle — icon sun/moon di dalam toggle button

### v2.2.0
- **Redesign website public** lengkap dengan Aveit-inspired design system
- Dual Vite entry points: `website.css` + `website.js` terpisah dari admin
- Alpine.js di-bundle via npm (hapus CDN), tambah `@alpinejs/collapse`
- Hero carousel pure Alpine (hapus Swiper CDN)
- Reusable partials: `section-heading`, `news-card`, `page-header`
- Custom AOS via IntersectionObserver (hapus AOS library dependency)
- Fix: AOS tidak jalan karena `DOMContentLoaded` tidak fired di module scripts
- Navigasi: sticky glass navbar, spring hover pill, animated active underline
- Auth: split-screen layout, Google Fonts (Quicksand + Nunito), improved theme toggle
- Hapus semua konten placeholder SaaS/AI dari home page (100% CMS-driven)
- Sidebar news: hapus newsletter non-fungsional → ganti CTA kontak

### v2.1.x
- Highcharts dashboard analytics (donut chart distribusi user)
- Livewire concerns: `WithNotifications`, `WithSearchablePagination`, `WithRateLimiting`
- Refactor dashboard UI, standardize button styling

### v2.0.x
- Redesign UI admin panel (Metronic-inspired)
- Custom pagination dengan per-page selector
- Media management system terpusat

---

## 📄 License

MIT License — open-sourced software.

## 👤 Author

Developed with Laravel 12 + Livewire 3 + Alpine.js
