<?php

namespace Alomgyar\Statistics;

use Illuminate\Support\ServiceProvider;

class StatisticsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'statistics');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'statistics');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/statistics'),
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Statistics\StatisticsController');
    }
}
