<?php

namespace Alomgyar\Suppliers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class SupplierServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'suppliers');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'suppliers');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/suppliers'),
        ]);
        Livewire::component('suppliers::listcomponent', ListComponent::class);
        Livewire::component('suppliers::uploadimage', UploadImageComponent::class);
        Livewire::component('suppliers::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Suppliers\SupplierController');
    }
}
