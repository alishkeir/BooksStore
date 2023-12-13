<?php

namespace Alomgyar\Customers;

use Alomgyar\Customers\Livewire\AddressesList;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CustomerServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'customers');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'customers');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/customers'),
        ]);
        Livewire::component('customers::listcomponent', ListComponent::class);
        Livewire::component('customers::uploadimage', UploadImageComponent::class);
        Livewire::component('customers::cards', CardsComponent::class);
        Livewire::component('customers::address-list', AddressesList::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Customers\CustomerController');
    }
}
