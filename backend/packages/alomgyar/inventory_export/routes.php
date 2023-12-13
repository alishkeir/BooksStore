<?php

use Alomgyar\InventoryExport\InventoryExportController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('inventory-count', [InventoryExportController::class, 'countPage'])->name('inventory_export.count');
            Route::get('inventory-product', [InventoryExportController::class, 'inventory'])->name('inventory_export.inventory');
            Route::get('inventory-export', [InventoryExportController::class, 'index'])->name('inventory_export.index');
            Route::get('download-inventory', [InventoryExportController::class, 'download'])->name('inventory_export.download');
            Route::get('inventory-product/create', [InventoryExportController::class, 'createProduct'])->name('inventory_export.create_product');
            Route::get('inventory-product/get-quantity', [InventoryExportController::class, 'createProductGetQuantity'])->name('inventory_export.create_product.get-quantity');
            Route::post('inventory-product/store', [InventoryExportController::class, 'storeProduct'])->name('inventory_export.store_product');
            Route::get('inventory-product/edit', [InventoryExportController::class, 'editProduct'])->name('inventory_export.edit_product');
            Route::post('inventory-product/update', [InventoryExportController::class, 'updateProduct'])->name('inventory_export.update_product');
            Route::delete('inventory-product-delete', [InventoryExportController::class, 'deleteProduct'])->name('inventory_export.delete_product');
        });
    });
});
