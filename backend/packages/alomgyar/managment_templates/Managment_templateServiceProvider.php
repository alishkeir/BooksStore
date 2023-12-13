<?php

namespace Alomgyar\Managment_templates;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class Managment_templateServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'managment_templates');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'managment_templates');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/managment_templates'),
        ]);
        Livewire::component('managment_templates::listcomponent', ListComponent::class);
        Livewire::component('managment_templates::uploadimage', UploadImageComponent::class);
        Livewire::component('managment_templates::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Managment_templates\Managment_templateController');
    }
}
