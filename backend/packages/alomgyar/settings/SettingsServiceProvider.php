<?php

namespace Alomgyar\Settings;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class SettingsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'settings');
        $this->loadViewsFrom(__DIR__.'/views/meta_data', 'metadata');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'settings');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/settings'),
        ]);
        Livewire::component('settings::listcomponent', ListComponent::class);
        Livewire::component('metadata::listcomponent', SettingsListMetaDataComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Settings\SettingsController');
    }
}
