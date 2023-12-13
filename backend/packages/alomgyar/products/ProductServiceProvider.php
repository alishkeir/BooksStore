<?php

namespace Alomgyar\Products;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ProductServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'products');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'products');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/products'),
        ]);
        Livewire::component('products::listcomponent', ListComponent::class);
        Livewire::component('products::uploadimage', UploadImageComponent::class);
        Livewire::component('products::flashcomponent', FlashComponent::class);
        Livewire::component('products::price', PriceComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Products\ProductController');
    }
}
