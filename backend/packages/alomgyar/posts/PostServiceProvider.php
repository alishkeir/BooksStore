<?php

namespace Alomgyar\Posts;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PostServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'posts');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'posts');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/posts'),
        ]);
        Livewire::component('posts::listcomponent', ListComponent::class);
        Livewire::component('posts::uploadimage', UploadImageComponent::class);
        Livewire::component('posts::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Posts\PostController');
    }
}
