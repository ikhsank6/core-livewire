# Laravel Livewire CMS Platform

> **v3** — Laravel + Livewire CMS with role-based access control and a public portal for a professional certification body (LSP): scheme list, certification flow, exam schedule, news and FAQ, plus redesigned sign-in screens. The look is built from the LSP logo colours (brand blue, sky blue and one gold accent on a deep navy ground). Content is backed by a reproducible, clearly labelled sample-data seeder.

## 📸 Screenshots

All images are 1440 × 900 (the phone view is 390 × 844) and use the sample data from `WebsiteContentSeeder`.

### Public website

| Home (hero) |
|---|
| ![Public website home page](docs/screenshots/website-home.png) |

| Schemes, light theme | Schemes, dark theme |
|---|---|
| ![Public website scheme list in light theme](docs/screenshots/website-skema.png) | ![Public website scheme list in dark theme](docs/screenshots/website-dark.png) |

| Phone (390 px) |
|---|
| <img src="docs/screenshots/website-mobile.png" alt="Public website on a phone" width="280"> |

### Sign-in and admin panel

| Login | Dashboard |
|---|---|
| ![Login page](docs/screenshots/login.png) | ![Admin dashboard](docs/screenshots/dashboard.png) |

| CMS — News |
|---|
| ![CMS news page](docs/screenshots/cms-news.png) |

## 🚀 Tech Stack

| Category | Technology |
|---|---|
| **Language** | PHP 8.2+ |
| **Framework** | Laravel 13 |
| **Reactive UI** | Livewire 4 (full-page components) |
| **CSS Framework** | Tailwind CSS 4 (Vite plugin) |
| **JavaScript** | Alpine.js 3 (npm bundle) + `@alpinejs/collapse` |
| **Admin UI** | Livewire Flux 2 + Filament Forms 5 (form schemas only) + Flowbite (CDN) |
| **Charts** | Highcharts 13 |
| **Typeface** | Inter (admin panel, public site and sign-in screens) |
| **Database** | SQLite (configurable → MySQL / PostgreSQL) |
| **Queue** | Database driver |
| **Email** | SMTP with queued notifications |
| **Build Tool** | Vite 8 (four entry points: admin CSS/JS and website CSS/JS) |
| **Tests** | PHPUnit 13 |

---

## ✨ Features

### 🔐 Authentication & Security

- Full auth flow: Login, Register, Forgot Password, Reset Password, Email Verification
- **Form card over a full navy background** — the form sits in a card on the right; on the left is an original illustration (an assessor beside a framed certificate, drawn for this project, not stock art). The same screen is used for every auth page
- **Theme switch** — one button with a sun and a moon icon; the gold knob slides to the active theme
- Queued email notifications (Mailtrap-ready)
- Standardized password validation (NIST/OWASP) with real-time strength indicator
- Rate limiting: auth routes 10 req/min, public website 60 req/min
- Role-based access control (RBAC)

### 🌐 Public Website (professional certification body)

A public portal themed for a professional certification body (LSP), with its own design system (`website.css` / `website.js`), entirely separate from the admin panel. Anything that looks like data (schemes, schedule, FAQ, sample articles) is clearly labelled as a sample, and no statistics, testimonials, partner logos or compliance claims are shown.

- **Home** — hero (headline, a search box and field tags that jump to the scheme list, certificate illustration, three quick-link tiles) → scheme list with a field filter → certification flow (five numbered steps, then the Competent / Not Yet Competent decision) → exam schedule → highlights (CMS carousel) → latest news → profile → FAQ → closing call to action
- **First screen fits the viewport** — on large screens the hero is as tall as the window minus the header, with the quick links resting on the bottom edge; on short windows the field tags are hidden and spacing tightens, so nothing is cut off on first load
- **Certificate illustration** — the three unit ticks draw in one after another and the KOMPETEN seal lands, on a looping 7 s cycle. The certificate itself stays still, and motion stops under `prefers-reduced-motion`
- **Dark navigation bar** — a key-shaped marker slides to the item you point at: raised while it moves, pressed in with a gold underline on the current page. One theme-switch button; the mobile menu opens as a full-width panel
- **News** — list (lead story plus cards), categories sidebar, article page with related news, and a dedicated pagination view (`views/vendor/pagination/website.blade.php`)
- **About Page** — company description, official channels, Google Maps embed
- **Dark/Light Mode** — persisted to localStorage, with no white flash on navigation
- **SEO** — meta tags, Open Graph, Twitter Cards
- **Responsive** — mobile-first, checked at 360 px, 390 px and 1440 px

**Design tokens** (sampled from the LSP logo; see [`design.md`](design.md) for the full system):

| Token | Value | Use |
|---|---|---|
| `brand` | `#2D68A7` | Logo blue: links, stamp ink |
| `sky` | `#4BA5DA` | Logo sky blue: links and focus on dark |
| `navy` / `navy-2` | `#162948` / `#273955` | Hero, footer, navigation bar, dark bands |
| `gold` | `#E7BA4B` | The single accent: primary actions, seal. Text on gold is `#14213A` |

Gold is never used as text on a light surface (contrast too low); `brand` is used there instead. All text pairs were checked against WCAG AA.

**Content Seeding**: `WebsiteContentSeeder` seeds a fictional LSP ("LSP Kompeta Nusantara", contact details on reserved `.example` domains), news categories and articles (titles start with "Contoh"), and three carousel slides. It is idempotent (matched on natural keys) and generates its own illustration SVGs, so `migrate:fresh --seed` is fully reproducible on a clean clone. Replace the sample content from the admin panel before going live.

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

- **Top navigation** instead of a sidebar: a blue header (logo, menu search with Ctrl/Cmd+K, date, notifications, profile) above a menu bar built from the Menu master — role-aware, with icons from the master, dropdown groups and a "More" overflow for items that do not fit. On small screens a hamburger opens a vertical drawer
- Depth: the header is a gradient of the logo blue with a lit top edge; the active menu item is a recessed tab with a short underline
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
- Node.js 20.19+ (or 22.12+) & NPM — required by Vite 8
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
│   ├── Layout/            # NotificationBell, NotificationIndex (Sidebar is unused: navigation is a top bar)
│   ├── Settings/          # System settings, Logs, Jobs
│   └── [Feature]/         # Feature-specific CRUD components
├── Models/                # 10 Eloquent models (all with UUID + soft deletes)
├── Repositories/          # Repository pattern (Interface → Implementation)
└── Services/MenuService.php

public/images/
└── guilloche.svg          # Security-print rosette, used only as a CSS mask

resources/
├── css/
│   ├── app.css            # Admin styles (Metronic/Flux/Filament)
│   └── website.css        # Website styles (LSP design system, separate from admin)
├── js/
│   ├── app.js             # Admin JS (Highcharts, Flowbite)
│   └── website.js         # Website JS (Alpine.js + collapse; interactions are inline in the views)
└── views/
    ├── components/
    │   ├── layouts/       # app.blade.php (admin, top navigation), auth.blade.php (form card on navy)
    │   └── ui/            # Reusable UI components
    ├── livewire/          # Livewire blade views
    ├── layouts/           # website.blade.php
    ├── partials/          # Shared partials (meta, favicon, theme scripts, admin top nav, ...)
    └── website/           # Public pages + partials (nav, hero, skema, alur, jadwal, sorotan, faq, cta, footer, ...)

routes/modules/
├── auth.php               # Authentication routes
├── cms.php                # CMS routes
├── master_data.php        # User/Role/Menu routes
├── settings.php           # Settings routes
└── website.php            # Public routes (rate limited)

docs/screenshots/          # Images used by this README
design.md                  # Design system (colours, type, components) for admin, site and auth
claude.md                  # Project conventions for AI assistants
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

### Using the Website Partials

Website partials live in `resources/views/website/partials/`. The reusable ones:

```blade
{{-- News card: variant is 'card' (default), 'lead' or 'row' --}}
@include('website.partials.news-card', ['item' => $newsItem, 'variant' => 'lead'])

{{-- Inner page header (the only <h1> on the page) --}}
@include('website.partials.page-header', [
    'title'      => 'Page title',
    'breadcrumb' => 'Breadcrumb label',
    'subtitle'   => 'Optional one-line description.',
])

{{-- Seal + organisation name; textClass overrides the name's colour and size --}}
@include('website.partials.logo', ['textClass' => 'font-extrabold text-lg text-white'])

{{-- Social icons; pass dark => true when sitting on a dark ground --}}
@include('website.partials.social-links', ['dark' => true])
```

Note: the hero search box currently only jumps to the scheme list (`#skema`); the typed text is not used to filter yet. The scheme list is filtered by field with the buttons beside it.

Home-page sections: `hero`, `skema`, `alur`, `jadwal`, `sorotan` (carousel), `faq`, `cta`. `skema`, `alur`, `jadwal` and `faq` hold static sample content in the view; `sorotan` and the news and profile blocks come from the CMS.

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

### v3.2.0
- **Colours from the LSP logo**: the public site, sign-in screens and admin header now share one palette sampled from the logo (brand blue `#2D68A7`, sky blue `#4BA5DA`, gold `#E7BA4B`, navy ground). Tokens were renamed (`rimba` → `navy`, `cap` → `gold`).
- **Dark navigation bar** on the public site with a sliding key-shaped marker (raised on hover, pressed in with a gold underline on the current page) and a one-button theme switch, also added to the sign-in screens.
- **Hero fits the first screen**: the hero fills the window below the header, the quick links became three icon tiles on the bottom edge, and short windows hide the field tags. The certificate no longer floats; its ticks and seal loop on a 7 s cycle.
- **Admin header and menu bar** gained depth: a gradient header with a lit top edge, and the active menu item as a recessed tab with a short underline.
- **Fix**: on 390 px phones the Menu button was pushed off-screen by the theme switch; the header row now fits down to 360 px.
- README screenshots were retaken.

### v3.1.0
- **Public site and sign-in screens redesigned for a professional certification body (LSP)**: scheme list with field filter, certification flow with the Competent / Not Yet Competent decision, exam schedule, highlights, FAQ, a guilloche security-print background and a stamp motif.
- `WebsiteContentSeeder` now seeds a fictional LSP with sample content labelled "Contoh", and retires the earlier public-service sample articles and categories.
- Sign-in screens: form card over a full navy background with an assessor illustration.

### v3.0.0
- **Public Website Redesign**: replaced the v2.2.x Aveit-inspired system with a standalone design system (`website.css`, `website.js`) and rebuilt every website view and partial. The visual identity of this release was itself replaced in v3.1.0 and v3.2.0 (see above).
- **Home page restructured** into a section-based, CMS-driven flow. The partials introduced here (`register-rail`, `services`, `testimonials`) no longer exist; the home page is now built from `hero`, `skema`, `alur`, `jadwal`, `sorotan`, `faq` and `cta`.
- **Custom Website Pagination**: Added `views/vendor/pagination/website.blade.php` styled to match the design tokens.
- **WebsiteContentSeeder**: New idempotent seeder for About Us, News categories/articles, and Carousels — self-generates placeholder illustrations so `migrate:fresh --seed` works on a clean clone. Wired into `DatabaseSeeder`.
- **Admin top navigation**: the sidebar was replaced by a two-row top navigation (header plus a menu bar built from the Menu master); global search supports the Ctrl/Cmd+K focus shortcut.
- **Auth Input Fixes**: Explicit border width/style on error-state input wrappers for consistent visibility in light and dark modes.
- **Session-Persistent CSP Nonces**: Content Security Policy nonces now persist per session with automatic injection into HTML `<script>` tags, building on the nonce-based script protection introduced in v2.4.0.
- **Dashboard Repository Refactor**: Dashboard stats decoupled from direct model access via the repository pattern; dashboard view access now restricted by role.

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

Developed with Laravel 13 + Livewire 4 + Alpine.js
