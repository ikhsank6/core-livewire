# Laravel Livewire 3 – Dynamic Role-Based Responsive Menu

A robust Laravel 11 + Livewire 3 application featuring a dynamic, role-based, responsive sidebar menu system.

## Tech Stack

-   **Framework**: Laravel 11
-   **Frontend**: Livewire 3 + TailwindCSS + AlpineJS
-   **UI Components**: FluxUI (partial) + Filament Forms (Schema only)
-   **Database**: SQLite

## Features

-   **Authentication**: Full login, register, password reset flows.
-   **Dynamic Sidebar**:
    -   Menu structure stored in database (`menus` table).
    -   Role-based visibility (`role_menu` pivot).
    -   Recursive multi-level support (currently optimized for 2 levels).
    -   Responsive: Collapsible on desktop, Off-canvas on mobile.
-   **Authorization**:
    -   `CheckMenuAccess` middleware for route protection.
    -   `MenuPolicy` and Gates.
-   **Performance**:
    -   Menu tree cached per role (`MenuService`).

## Installation

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
    ```

3. **Database Migration & Seeding**

    ```bash
    php artisan migrate:fresh --seed
    ```

    **Default Credentials:**

    - **Super Admin**: `superadmin@example.com` / `password`

4. **Run Application**
    ```bash
    npm run dev
    # In another terminal
    php artisan serve
    ```

## Project Structure

-   `app/Services/MenuService.php`: Core logic for building and caching the menu tree.
-   `app/Livewire/Layout/Sidebar.php`: The reactive sidebar component.
-   `app/Http/Middleware/CheckMenuAccess.php`: middleware to enforce menu permissions on routes.
-   `app/Forms/`: Filament form schemas (UserForm, RoleForm, MenuForm).

## Usage

-   **Add Menu**: Insert into `menus` table.
-   **Assign to Role**: Insert into `role_menu`.
-   **Protect Route**: Add route to `web.php` inside the `auth` & `menu.access` middleware group. Ensure route name matches `menus.route`.
