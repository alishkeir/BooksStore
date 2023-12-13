<?php

namespace Alomgyar\Recommenders;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class RecommendersServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'recommenders');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'recommenders');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/recommenders'),
        ]);
        /** LiveWire components */
        Livewire::component('recommenders::listcomponent', ListComponent::class);
        Livewire::component('recommenders::cards', CardsComponent::class);
        Livewire::component('recommenders::productSearch', ProductSearchComponent::class);
        Livewire::component('recommenders::form', FormComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Recommenders\RecommendersController');
    }
}
