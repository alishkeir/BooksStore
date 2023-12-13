<?php

namespace Alomgyar\Carousels;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CarouselServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'carousels');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'carousels');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/carousels'),
        ]);
        Livewire::component('carousels::listcomponent', CarouselComponent::class);
        Livewire::component('carousels::uploadimage', UploadImageComponent::class);
        Livewire::component('carousels::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Carousels\CarouselController');
    }
}
