<?php

namespace Alomgyar\Product_movements;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ProductMovementServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'product_movements');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'product_movements');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/product_movements'),
        ]);
        Livewire::component('product_movements::listcomponent', ListComponent::class);
        Livewire::component('product_movements::uploadimage', UploadImageComponent::class);
        Livewire::component('product_movements::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Product_movements\ProductMovementController');
    }
}
