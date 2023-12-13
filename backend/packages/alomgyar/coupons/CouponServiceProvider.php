<?php

namespace Alomgyar\Coupons;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CouponServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/views', 'coupons');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'coupons');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/skvadcom/coupons'),
        ]);
        Livewire::component('coupons::listcomponent', ListComponent::class);
        Livewire::component('coupons::uploadimage', UploadImageComponent::class);
        Livewire::component('coupons::cards', CardsComponent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('Alomgyar\Coupons\CouponController');
    }
}
