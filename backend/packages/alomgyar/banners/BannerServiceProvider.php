<?php

namespace Alomgyar\Banners;

use Alomgyar\Banners\Controllers\BannersController;
use Alomgyar\Banners\Livewire\BannerComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class BannerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'banners');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'banners');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/banners'),
        ]);

        Livewire::component('banners::form', BannerComponent::class);
    }

    public function boot(): void
    {
        $this->app->make(BannersController::class);
    }
}
