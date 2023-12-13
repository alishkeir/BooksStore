<?php

namespace Skvadcom\Items;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ItemServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'items');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'items');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/items'),
        ]);
        Livewire::component('items::listcomponent', ListComponent::class);
        Livewire::component('items::uploadimage', UploadImageComponent::class);
        Livewire::component('items::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Skvadcom\Items\ItemController');
    }
}
