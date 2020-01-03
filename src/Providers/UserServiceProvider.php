<?php

namespace ConfrariaWeb\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'user');
        $this->loadMigrationsFrom(__DIR__ . '/../Databases');
        $this->publishes([__DIR__ . '/../../config/cw_user.php' => config_path('cw_user.php')], 'cw_user');


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

}
