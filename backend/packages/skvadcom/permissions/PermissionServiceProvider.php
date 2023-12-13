<?php

namespace Skvadcom\Permissions;

use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'permissions');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'permissions');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/permissions'),
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Skvadcom\Permissions\PermissionController');
    }
}
