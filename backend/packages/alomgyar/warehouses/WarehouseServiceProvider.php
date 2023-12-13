<?php

namespace Alomgyar\Warehouses;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class WarehouseServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'warehouses');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'warehouses');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/warehouses'),
        ]);
        Livewire::component('warehouses::listcomponent', ListComponent::class);
        Livewire::component('warehouses::uploadimage', UploadImageComponent::class);
        Livewire::component('warehouses::cards', CardsComponent::class);
        Livewire::component('warehouses::stockIn', StockInComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Warehouses\WarehouseController');
    }
}
