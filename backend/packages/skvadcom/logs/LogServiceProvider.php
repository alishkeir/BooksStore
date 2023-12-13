<?php

namespace Skvadcom\Logs;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LogServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'logs');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/logs'),
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Skvadcom\Logs\LogController');
        Livewire::component('logs::listcomponent', LogComponent::class);
    }
}
