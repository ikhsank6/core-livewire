<?php

use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profile;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Menus\MenuIndex;
use App\Livewire\Menus\RoleMenuAccess;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware(['auth', 'menu.access'])->group(function () {
    Route::get('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    })->name('logout');

    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/password/change', ChangePassword::class)->name('password.change');

    Route::get('/roles/switch/{role}', function (\App\Models\Role $role) {
        auth()->user()->setActiveRole($role);
        app(\App\Services\MenuService::class)->clearMenuCache($role->id);

        return back()->with('success', "Switched to active role: {$role->name}");
    })->name('roles.switch');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master Data
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/roles', RoleIndex::class)->name('roles.index');
    Route::get('/menus', MenuIndex::class)->name('menus.index');
    Route::get('/menu-access', RoleMenuAccess::class)->name('menu-access.index');
});
