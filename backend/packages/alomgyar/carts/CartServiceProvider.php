<?php

namespace Alomgyar\Carts;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CartServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'carts');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'carts');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/alomgyar/carts'),
        ]);
        //Livewire::component('carts::listcomponent', ListComponent::class);
        //Livewire::component('carts::uploadimage', UploadImageComponent::class);
        //Livewire::component('carts::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Carts\CartController');
    }
}
