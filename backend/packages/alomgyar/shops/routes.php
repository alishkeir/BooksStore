<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::delete('shops/destroy', '\Alomgyar\Shops\ShopController@destroy')->name('shops.destroy')->middleware(['can:shops.destroy']);
            Route::get('shops/search', '\Alomgyar\Shops\ShopController@search')->name('shops.search')->middleware(['can:shops.index']);
            Route::resource('shops', '\Alomgyar\Shops\ShopController')->middleware(['can:shops.index'])->except(['destroy']);
            Route::get('shop', '\Alomgyar\Shops\ShopController@shop')->name('shop')->middleware(['can:shop']);
        });
    });
});
