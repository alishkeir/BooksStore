<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Alomgyar\Product_movements\ProductMovementController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::delete('product_movements/destroy', [ProductMovementController::class, 'destroy'])->name('product_movements.destroy')->middleware(['can:product_movements.destroy']);
            Route::get('product_movements/export/{ProductMovement}', [ProductMovementController::class, 'export'])->name('product_movements.export')->middleware(['can:product_movements.export']);
            Route::resource('warehouses/product_movements',
                '\Alomgyar\Product_movements\ProductMovementController')->middleware(['can:product_movements.index'])->except(['destroy']);
            //Route::get('product_movements', '\Alomgyar\Product_movements\Product_movementController@index')->name('product_movements.index')->middleware(['can:product_movements.index']);
            //Route::get('product_movements/show', '\Alomgyar\Product_movements\Product_movementController@show')->name('product_movements.show')->middleware(['can:product_movements.show']);
            //Route::resource('product_movements', '\Alomgyar\Product_movements\Product_movementController')->only(['create','store', 'edit', 'update'])->middleware(['can:product_movements.storing']);
        });
    });
});
