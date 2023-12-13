<?php

namespace Alomgyar\Methods;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class MethodsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'methods');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'methods');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/methods'),
        ]);
        //Livewire::component('methods::listcomponent', ListComponent::class);
        Livewire::component('methods::cards-shipping', CardsShippingComponent::class);
        Livewire::component('methods::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Methods\MethodsController');
    }
}
