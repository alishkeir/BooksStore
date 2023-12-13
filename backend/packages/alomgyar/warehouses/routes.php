<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Alomgyar\Warehouses\StockInComponent;
use Alomgyar\Warehouses\WarehouseController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::delete('warehouses/destroy', [WarehouseController::class, 'destroy'])->name('warehouses.destroy')->middleware(['can:warehouses.destroy']);
            Route::get('warehouses/{warehouse_id}/restore', [WarehouseController::class, 'restore'])->name('warehouses.restore')->middleware(['can:warehouses.restore']);
            Route::get('warehouses/stock-in/{productId?}', StockInComponent::class)->name('warehouses.stock-in')->middleware(['can:warehouses.stock-in']);
            Route::get('warehouses/export/{warehouseID}', [WarehouseController::class, 'export'])->name('warehouses.export')->middleware(['can:warehouses.export']);
            Route::get('warehouses/import', [WarehouseController::class, 'import'])->name('warehouses.import')->middleware(['can:warehouses.import']);
            Route::put('warehouses/import', [WarehouseController::class, 'importProducts'])->name('warehouses.process-import');
            Route::post('warehouses/import', [WarehouseController::class, 'runImport'])->name('warehouses.run-import');
            Route::get('warehouses/search', [WarehouseController::class, 'search'])->name('warehouses.search');
            Route::get('warehouses/search-product', [WarehouseController::class, 'searchProduct'])->name('warehouses.product.search');
            Route::resource('warehouses', WarehouseController::class)->middleware(['can:warehouses.index'])->except(['destroy']);
        });
    });
});
