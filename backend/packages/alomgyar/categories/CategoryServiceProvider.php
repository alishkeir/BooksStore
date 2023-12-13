<?php

namespace Alomgyar\Categories;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CategoryServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'categories');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'categories');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/categories'),
        ]);
        Livewire::component('categories::listcomponent', ListComponent::class);
        Livewire::component('categories::uploadimage', UploadImageComponent::class);
        Livewire::component('categories::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Categories\CategoryController');
    }
}
