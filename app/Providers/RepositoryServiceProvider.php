<?php

namespace App\Providers;

use App\Repositories\AboutUsRepository;
use App\Repositories\CarouselRepository;
use App\Repositories\Contracts\AboutUsRepositoryInterface;
use App\Repositories\Contracts\CarouselRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\NewsCategoryRepositoryInterface;
use App\Repositories\Contracts\NewsRepositoryInterface;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\SystemSettingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\MenuRepository;
use App\Repositories\NewsCategoryRepository;
use App\Repositories\NewsRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SystemSettingRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All repository bindings.
     *
     * @var array<string, string>
     */
    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        MenuRepositoryInterface::class => MenuRepository::class,
        NotificationRepositoryInterface::class => NotificationRepository::class,
        CarouselRepositoryInterface::class => CarouselRepository::class,
        NewsCategoryRepositoryInterface::class => NewsCategoryRepository::class,
        NewsRepositoryInterface::class => NewsRepository::class,
        AboutUsRepositoryInterface::class => AboutUsRepository::class,
        SystemSettingRepositoryInterface::class => SystemSettingRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
