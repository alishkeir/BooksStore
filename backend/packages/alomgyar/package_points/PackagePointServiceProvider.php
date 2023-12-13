<?php

namespace Alomgyar\PackagePoints;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class PackagePointServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'package_points');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'package-points');
    }

    public function boot()
    {
        Paginator::useBootstrap();
    }
}
