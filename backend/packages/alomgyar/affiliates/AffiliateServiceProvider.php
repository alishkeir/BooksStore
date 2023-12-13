<?php

namespace Alomgyar\Affiliates;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AffiliateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadViewsFrom(__DIR__ . '/views', 'affiliates');
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'affiliates');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/skvadcom/affiliates'),
        ]);
        Livewire::component('affiliates::listcustomers', ListCustomers::class);
        Livewire::component('affiliates::listredeems', ListRedeems::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Affiliates\AffiliateController');
    }
}