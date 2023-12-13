<?php

namespace Alomgyar\Subcategories;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class SubcategoryServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'subcategories');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'subcategories');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/subcategories'),
        ]);
        Livewire::component('subcategories::listcomponent', ListComponent::class);
        Livewire::component('subcategories::uploadimage', UploadImageComponent::class);
        Livewire::component('subcategories::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Subcategories\SubcategoryController');
    }
}
