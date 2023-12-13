<?php

namespace Alomgyar\Countries;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CountryServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'countries');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'countries');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/countries'),
        ]);
        Livewire::component('countries::listcomponent', ListComponent::class);
        Livewire::component('countries::uploadimage', UploadImageComponent::class);
        Livewire::component('countries::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Countries\CountryController');
    }
}
