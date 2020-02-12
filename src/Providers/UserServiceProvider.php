<?php

namespace ConfrariaWeb\User\Providers;

use App\User;
use ConfrariaWeb\User\Commands\CheckPackage;
use ConfrariaWeb\User\Contracts\UserContract;
use ConfrariaWeb\User\Contracts\UserStatusContract;
use ConfrariaWeb\User\Contracts\UserStepContract;
use ConfrariaWeb\User\Observers\UserObserver;
use ConfrariaWeb\User\Repositories\UserRepository;
use ConfrariaWeb\User\Repositories\UserStatusRepository;
use ConfrariaWeb\User\Repositories\UserStepRepository;
use ConfrariaWeb\User\Services\UserService;
use ConfrariaWeb\User\Services\UserStatusService;
use ConfrariaWeb\User\Services\UserStepService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(UserContract::class, UserRepository::class);
        $this->app->bind('UserService', function ($app) {
            return new UserService($app->make(UserContract::class));
        });

        $this->app->bind(UserStatusContract::class, UserStatusRepository::class);
        $this->app->bind('UserStatusService', function ($app) {
            return new UserStatusService($app->make(UserStatusContract::class));
        });
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'user');
        $this->loadMigrationsFrom(__DIR__ . '/../Databases/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Translations', 'user');
        $this->publishes([__DIR__ . '/../../config/cw_user.php' => config_path('cw_user.php')], 'cw_user');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckPackage::class
            ]);
        }

        User::observe(UserObserver::class);
    }

}
