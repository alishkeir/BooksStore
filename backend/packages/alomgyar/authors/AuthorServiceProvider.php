<?php

namespace Alomgyar\Authors;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AuthorServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'authors');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'authors');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/authors'),
        ]);
        Livewire::component('authors::listcomponent', ListComponent::class);
        Livewire::component('authors::uploadimage', UploadImageComponent::class);
        Livewire::component('authors::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Authors\AuthorController');
    }
}
