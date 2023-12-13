<?php

namespace Alomgyar\InventoryExport;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class InventoryExportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'inventory_export');

        Livewire::component('inventory_export::inventory-count', InventoryCountComponent::class);
        Livewire::component('inventory_export::inventory-list', InventoryListComponent::class);
    }
}
