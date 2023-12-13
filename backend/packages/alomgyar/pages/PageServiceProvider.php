<?php

namespace Alomgyar\Pages;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PageServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'pages');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'pages');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/pages'),
        ]);
        Livewire::component('pages::listcomponent', ListComponent::class);
        Livewire::component('pages::uploadimage', UploadImageComponent::class);
        Livewire::component('pages::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Pages\PageController');
    }
}
