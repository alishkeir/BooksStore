<?php

namespace Alomgyar\Legal_owners;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LegalOwnerServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'legal_owners');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'legal_owners');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/alomgyar/legal_owners'),
        ]);
        Livewire::component('legal_owners::listcomponent', ListComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Legal_owners\LegalOwnerController');
    }
}
