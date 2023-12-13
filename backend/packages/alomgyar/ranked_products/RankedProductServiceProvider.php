<?php

namespace Alomgyar\RankedProducts;

use Carbon\Laravel\ServiceProvider;

class RankedProductServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}
