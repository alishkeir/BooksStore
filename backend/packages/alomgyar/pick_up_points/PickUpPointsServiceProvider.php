<?php

namespace Alomgyar\PickUpPoints;

use Illuminate\Support\ServiceProvider;

class PickUpPointsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}
