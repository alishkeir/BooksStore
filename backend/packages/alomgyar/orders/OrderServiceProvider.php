<?php

namespace Alomgyar\Orders;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class OrderServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'orders');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'orders');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/orders'),
        ]);
        Livewire::component('orders::listcomponent', ListComponent::class);
        Livewire::component('orders::listitemcomponent', ListItemComponent::class);
        Livewire::component('orders::createcomponent', CreateComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Orders\OrderController');
    }
}
