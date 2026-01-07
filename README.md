# Laravel Livewire 3 – Dynamic Role-Based CMS Application

A comprehensive Laravel 11 + Livewire 3 application featuring a dynamic role-based menu system, CMS functionality, and a modern public website with dark mode support.

## 🚀 Tech Stack

| Category          | Technology                            |
| ----------------- | ------------------------------------- |
| **Framework**     | Laravel 11                            |
| **Frontend**      | Livewire 3 + TailwindCSS 4 + AlpineJS |
| **UI Components** | FluxUI + Filament Forms               |
| **Database**      | SQLite (configurable)                 |
| **Queue**         | Database Driver                       |
| **Email**         | SMTP with custom templates            |

## ✨ Features

### 🔐 Authentication & Security

-   Full authentication flow (Login, Register, Forgot Password, Reset Password)
-   Email verification with custom branded templates
-   Queued email notifications
-   Rate limiting protection on public and auth routes
-   Role-based access control (RBAC)

### 📱 Dynamic Sidebar Menu

-   Menu structure stored in database (`menus` table)
-   Role-based visibility via `role_menu` pivot table
-   Recursive multi-level support
-   Drag-and-drop reordering
-   Responsive: Collapsible on desktop, Off-canvas on mobile
-   Icon support with Heroicons

### 🌐 Public Website

-   **Home Page**: Hero carousel with AOS animations
-   **News Section**: Featured articles, categories, detail pages
-   **About Page**: Company information with map integration
-   **Dark/Light Mode**: Toggle with smooth transitions
-   **SEO Optimized**: Meta tags, Open Graph, Twitter Cards

### 📰 CMS (Content Management System)

-   **News Management**: Create, edit, publish articles with rich text editor
-   **News Categories**: Organize articles by categories
-   **Carousels**: Homepage slider management with drag-and-drop ordering
-   **About Us**: Company profile, contact info, social links, logo

### ⚙️ Settings & Configuration

-   **System Settings**: Favicon, SEO metadata, Google Analytics
-   **Log Viewer**: View Laravel logs with filtering and search (Opcodes Log Viewer)

### 👥 Master Data Management

-   **User Management**: CRUD with avatar, role assignment, activation status
-   **Role Management**: Create roles and assign menu access
-   **Menu Management**: Dynamic menu builder with drag-and-drop

### 🎨 UI/UX Features

-   Modern glassmorphism design
-   Dark mode support throughout
-   Reusable UI components (buttons, tables, cards, modals)
-   Toast notifications
-   Empty state components
-   Pagination with "Jump to Page"
-   Table/Card view toggle

### 🔔 Notifications

-   In-app notification system
-   Notification bell with unread count
-   Notification inbox with read/unread filters

### 📧 Email Templates

-   Modern Tokopedia-style email design
-   Branded with company logo
-   Responsive for all email clients
-   Templates for: Verify Email, Reset Password

## 📦 Installation

### Prerequisites

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   SQLite (or MySQL/PostgreSQL)

### Steps

1. **Clone & Install Dependencies**

    ```bash
    git clone <repo>
    cd <repo>
    composer install
    npm install
    ```

2. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    touch database/database.sqlite
    php artisan storage:link
    ```

3. **Configure Environment**

    ```env
    APP_URL=http://localhost:8000

    DB_CONNECTION=sqlite

    MAIL_MAILER=smtp
    MAIL_HOST=your-smtp-host
    MAIL_PORT=587
    MAIL_USERNAME=your-username
    MAIL_PASSWORD=your-password
    MAIL_FROM_ADDRESS=noreply@example.com
    ```

4. **Database Migration & Seeding**

    ```bash
    php artisan migrate:fresh --seed
    ```

    **Default Credentials:**
    | Role | Email | Password |
    |------|-------|----------|
    | Super Admin | `superadmin@example.com` | `password` |

5. **Run Application**

    ```bash
    # Terminal 1: Assets
    npm run dev

    # Terminal 2: Server
    php artisan serve

    # Terminal 3: Queue Worker (for emails)
    php artisan queue:work
    ```

## 📁 Project Structure

```
app/
├── Actions/
│   └── Website/           # Public website action classes
├── Forms/                 # Filament form schemas
├── Http/
│   └── Middleware/
│       └── CheckMenuAccess.php
├── Livewire/
│   ├── Auth/              # Authentication components
│   ├── Layout/            # Sidebar, Notifications
│   ├── Settings/          # System settings, Logs
│   └── [Feature]/         # Feature-specific components
├── Models/                # Eloquent models
├── Notifications/         # Email notification classes
├── Repositories/          # Repository pattern implementation
└── Services/
    └── MenuService.php    # Menu caching logic

resources/views/
├── components/
│   ├── emails/           # Email template components
│   ├── layouts/          # App layout
│   └── ui/               # Reusable UI components
├── emails/               # Email templates
├── livewire/             # Livewire views
├── partials/             # Shared partials
└── website/              # Public website views

routes/
└── modules/
    ├── auth.php          # Authentication routes
    ├── cms.php           # CMS routes
    ├── master_data.php   # Master data routes
    ├── settings.php      # Settings routes
    └── website.php       # Public website routes
```

## 🛠️ Usage

### Adding a New Menu

1. Navigate to **Master Data > Menus**
2. Click **Add Menu**
3. Fill in: Name, Route, Icon, Parent (optional)
4. Assign to roles via **Master Data > Roles**

### Protecting Routes

Add routes inside the `auth` & `menu.access` middleware group:

```php
Route::middleware(['auth', 'menu.access'])->group(function () {
    Route::get('/your-route', YourComponent::class)->name('your.route');
});
```

### Rate Limiting

Public routes are protected with rate limiting:

-   **Website routes**: 60 requests/minute/IP
-   **Auth routes**: 10 requests/minute/IP

## 🔧 Artisan Commands

```bash
# Clear all caches
php artisan optimize:clear

# Run queue worker
php artisan queue:work

# Retry failed jobs
php artisan queue:retry all

# View logs
php artisan log-viewer:publish
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 👤 Author

Developed with ❤️ using Laravel & Livewire
