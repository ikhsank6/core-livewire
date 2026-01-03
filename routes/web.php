<?php

use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profile;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Layout\NotificationIndex;
use App\Livewire\Menus\MenuIndex;
use App\Livewire\Menus\RoleMenuAccess;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('reset-password');
});

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
});
