<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('items', '\Skvadcom\Items\ItemController')->middleware(['can:items.index']);
            //Route::get('items', '\Skvadcom\Items\ItemController@index')->name('items.index')->middleware(['can:items.index']);
            //Route::get('items/show', '\Skvadcom\Items\ItemController@show')->name('items.show')->middleware(['can:items.show']);
            //Route::resource('items', '\Skvadcom\Items\ItemController')->only(['create','store', 'edit', 'update'])->middleware(['can:items.storing']);
            Route::delete('items/destroy', '\Skvadcom\Items\ItemController@destroy')->name('items.destroy')->middleware(['can:items.destroy']);
        });
    });
}); */
