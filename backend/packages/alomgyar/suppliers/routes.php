<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Alomgyar\Suppliers\SupplierController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::delete('suppliers/destroy', [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware(['can:suppliers.destroy']);
            Route::get('suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search')->middleware(['can:suppliers.index']);
            Route::post('suppliers/download/inventory/{supplier}', [SupplierController::class, 'downloadInventory'])->name('suppliers.download.inventory')->middleware(['can:suppliers.index']);
            Route::resource('suppliers', SupplierController::class)->middleware(['can:suppliers.index'])->except(['destroy']);
            //Route::get('suppliers', '\Alomgyar\Suppliers\SupplierController@index')->name('suppliers.index')->middleware(['can:suppliers.index']);
            //Route::get('suppliers/show', '\Alomgyar\Suppliers\SupplierController@show')->name('suppliers.show')->middleware(['can:suppliers.show']);
            //Route::resource('suppliers', '\Alomgyar\Suppliers\SupplierController')->only(['create','store', 'edit', 'update'])->middleware(['can:suppliers.storing']);
        });
    });
});
