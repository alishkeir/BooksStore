<?php

namespace Alomgyar\Templates;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class TemplatesServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'templates');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'templates');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/templates'),
        ]);
        Livewire::component('templates::listcomponent', ListComponent::class);
        Livewire::component('templates::uploadimage', UploadImageComponent::class);
        Livewire::component('templates::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Templates\TemplatesController');
    }
}
