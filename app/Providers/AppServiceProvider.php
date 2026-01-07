<?php

namespace App\Providers;

use App\Models\Menu;
use App\Policies\MenuPolicy;
use App\Services\MenuService;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected array $policies = [
        Menu::class => MenuPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MenuService::class, function ($app) {
            return new MenuService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Indigo,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ]);

        $this->registerPolicies();
        $this->registerGates();

        // Share cached system settings and about us with all views
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $systemSettingRepo = app(\App\Repositories\Contracts\SystemSettingRepositoryInterface::class);
            $aboutUsRepo = app(\App\Repositories\Contracts\AboutUsRepositoryInterface::class);

            $view->with('settings', $systemSettingRepo->getCachedSettings());
            $view->with('aboutUs', $aboutUsRepo->getCached());
        });
    }

    /**
     * Register the application's policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Register custom gates.
     */
    protected function registerGates(): void
    {
        Gate::define('access-route', function ($user, string $routeName) {
            $menuService = app(MenuService::class);

            return $menuService->userHasAccessToRoute($routeName);
        });

        Gate::define('access-menu', function ($user, $menu) {
            if (! $user->role) {
                return false;
            }

            return $user->role->menus()->where('menus.id', $menu->id)->exists();
        });
    }
}
