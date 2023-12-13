<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Alomgyar\Customers\CustomerController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
            Route::resource('customers', '\Alomgyar\Customers\CustomerController')->middleware(['can:customers.index'])->except(['destroy']);
            //Route::get('customers', '\Alomgyar\Customers\CustomerController@index')->name('customers.index')->middleware(['can:customers.index']);
            //Route::get('customers/show', '\Alomgyar\Customers\CustomerController@show')->name('customers.show')->middleware(['can:customers.show']);
            //Route::resource('customers', '\Alomgyar\Customers\CustomerController')->only(['create','store', 'edit', 'update'])->middleware(['can:customers.storing']);
            Route::delete('customers/destroy', '\Alomgyar\Customers\CustomerController@destroy')->name('customers.destroy')->middleware(['can:customers.destroy']);
        });
    });
});
