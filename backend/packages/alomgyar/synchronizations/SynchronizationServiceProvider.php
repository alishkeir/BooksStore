<?php

namespace Alomgyar\Synchronizations;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class SynchronizationServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'synchronizations');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'synchronizations');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/synchronizations'),
        ]);
        Livewire::component('synchronizations::listcomponent', ListComponent::class);
        Livewire::component('synchronizations::synccomponent', SyncComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Synchronizations\SynchronizationController');
    }
}
