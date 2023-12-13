<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('carts', '\Alomgyar\Carts\CartController');
            //Route::get('carts', '\Alomgyar\Carts\CartController@index')->name('carts.index')->middleware(['can:carts.index']);
            //Route::get('carts/show', '\Alomgyar\Carts\CartController@show')->name('carts.show')->middleware(['can:carts.show']);
            //Route::resource('carts', '\Alomgyar\Carts\CartController')->only(['create','store', 'edit', 'update'])->middleware(['can:carts.storing']);
            //Route::delete('carts/destroy', '\Alomgyar\Carts\CartController@destroy')->name('carts.destroy');
        });
    });
});
