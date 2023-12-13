<?php

namespace Alomgyar\Comments;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CommentServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'comments');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'comments');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/comments'),
        ]);
        Livewire::component('comments::listcomponent', ListComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Comments\CommentController');
    }
}
