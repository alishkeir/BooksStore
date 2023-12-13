<?php

namespace Alomgyar\Writers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class WriterServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'writers');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'writers');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/writers'),
        ]);
        Livewire::component('writers::listcomponent', ListComponent::class);
        Livewire::component('writers::uploadimage', UploadImageComponent::class);
        Livewire::component('writers::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Writers\WriterController');
    }
}
