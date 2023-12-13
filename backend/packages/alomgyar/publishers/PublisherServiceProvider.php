<?php

namespace Alomgyar\Publishers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PublisherServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'publishers');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'publishers');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/publishers'),
        ]);
        Livewire::component('publishers::listcomponent', ListComponent::class);
        Livewire::component('publishers::uploadimage', UploadImageComponent::class);
        Livewire::component('publishers::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Publishers\PublisherController');
    }
}
