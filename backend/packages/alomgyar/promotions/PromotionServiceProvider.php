<?php

namespace Alomgyar\Promotions;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PromotionServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'promotions');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'promotions');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/promotions'),
        ]);
        Livewire::component('promotions::cards', CardsComponent::class);
        Livewire::component('promotions::products', PromotionComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Promotions\PromotionController');
    }
}
