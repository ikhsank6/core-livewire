<?php

use App\Actions\SwitchRoleAction;
use App\Actions\Website\ShowAboutPage;
use App\Actions\Website\ShowHomePage;
use App\Actions\Website\ShowNewsDetail;
use App\Actions\Website\ShowNewsList;
use App\Livewire\AboutUs\AboutUsIndex;
use App\Livewire\Carousels\CarouselIndex;
use App\Livewire\Dashboard;
use App\Livewire\Layout\NotificationIndex;
use App\Livewire\Menus\MenuIndex;
use App\Livewire\Menus\RoleMenuAccess;
use App\Livewire\News\NewsIndex;
use App\Livewire\NewsCategories\NewsCategoryIndex;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', ShowHomePage::class)->name('landing');
Route::get('/news', ShowNewsList::class)->name('news.index');
Route::get('/news/{slug}', ShowNewsDetail::class)->name('news.show');
Route::get('/about', ShowAboutPage::class)->name('about');

/*
|--------------------------------------------------------------------------
| Authentication Modules
|--------------------------------------------------------------------------
*/

require base_path('routes/modules/auth.php');

/*
|--------------------------------------------------------------------------
| Secured Routes
|--------------------------------------------------------------------------
*/

// Routes that need auth but NOT menu.access restriction
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', NotificationIndex::class)->name('notifications.index');

    // Switch role - accessible to all authenticated users regardless of menu access
    Route::get('/roles/switch/{role}', SwitchRoleAction::class)->name('roles.switch');
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

    // CMS Management Routes
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/carousels', CarouselIndex::class)->name('carousels.index');
        Route::get('/news-categories', NewsCategoryIndex::class)->name('news-categories.index');
        Route::get('/news', NewsIndex::class)->name('news.index');
        Route::get('/about-us', AboutUsIndex::class)->name('about-us.index');
    });
});
