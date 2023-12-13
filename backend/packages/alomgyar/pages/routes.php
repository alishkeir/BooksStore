<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::delete('pages/destroy', '\Alomgyar\Pages\PageController@destroy')->name('pages.destroy')->middleware(['can:pages.destroy']);
            Route::resource('pages', '\Alomgyar\Pages\PageController')->middleware(['can:pages.index'])->except(['destroy']);
            //Route::get('pages', '\Alomgyar\Pages\PageController@index')->name('pages.index')->middleware(['can:pages.index']);
            //Route::get('pages/show', '\Alomgyar\Pages\PageController@show')->name('pages.show')->middleware(['can:pages.show']);
            //Route::resource('pages', '\Alomgyar\Pages\PageController')->only(['create','store', 'edit', 'update'])->middleware(['can:pages.storing']);
        });
    });
});
