<?php

namespace Alomgyar\BookRecommendation;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class BookRecommendationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'bookrecommendation');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/bookrecommendation'),
        ]);
        Livewire::component('bookrecommendation::component', BookRecommendationComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\BookRecommendation\BookRecommendationController');
    }
}
