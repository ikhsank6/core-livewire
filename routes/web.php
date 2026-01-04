<?php

use App\Livewire\AboutUs\AboutUsIndex;
use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profile;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Carousels\CarouselIndex;
use App\Livewire\Dashboard;
use App\Livewire\Layout\NotificationIndex;
use App\Livewire\Menus\MenuIndex;
use App\Livewire\Menus\RoleMenuAccess;
use App\Livewire\News\NewsIndex;
use App\Livewire\NewsCategories\NewsCategoryIndex;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Users\UserIndex;
use App\Livewire\Website\LandingPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing Page (Public)
Route::get('/', LandingPage::class)->name('landing');

Route::middleware('guest')->prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Alias for Laravel's default 'login' route name requirement
Route::get('/login', function () {
    return redirect()->route('auth.login');
})->name('login');

// Email verification route
Route::get('/auth/verify-email/{id}/{hash}', VerifyEmail::class)
    ->middleware(['throttle:6,1'])
    ->name('auth.verification.verify');

// Routes that need auth but NOT menu.access restriction
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('auth.login');
    })->name('logout');

    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/password/change', ChangePassword::class)->name('password.change');
    Route::get('/notifications', NotificationIndex::class)->name('notifications.index');

    // Switch role - accessible to all authenticated users regardless of menu access
    Route::get('/roles/switch/{role}', function (\App\Models\Role $role) {
        $user = auth()->user();

        // Verify user has this role
        if (! $user->roles->contains('id', $role->id)) {
            return back()->with('error', 'Unauthorized role switch.');
        }

        $user->setActiveRole($role);
        app(\App\Services\MenuService::class)->clearMenuCache($role->id);

        return redirect()->route('dashboard')->with('success', "Switched to active role: {$role->name}");
    })->name('roles.switch');
});

// Routes that need both auth AND menu.access restriction
Route::middleware(['auth', 'menu.access'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master Data Routes
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/roles', RoleIndex::class)->name('roles.index');
        Route::get('/menus', MenuIndex::class)->name('menus.index');
        Route::get('/menu-access', RoleMenuAccess::class)->name('menu-access.index');
    });

    // Website Management Routes
    Route::prefix('website')->name('website.')->group(function () {
        Route::get('/carousels', CarouselIndex::class)->name('carousels.index');
        Route::get('/news-categories', NewsCategoryIndex::class)->name('news-categories.index');
        Route::get('/news', NewsIndex::class)->name('news.index');
        Route::get('/about-us', AboutUsIndex::class)->name('about-us.index');
    });
});
