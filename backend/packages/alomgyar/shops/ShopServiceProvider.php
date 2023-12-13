<?php

namespace Alomgyar\Shops;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ShopServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'shops');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'shop');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'shops');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/shops'),
        ]);
        Livewire::component('shops::listcomponent', ListComponent::class);
        Livewire::component('shops::shopcomponent', ShopComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Shops\ShopController');
    }
}
