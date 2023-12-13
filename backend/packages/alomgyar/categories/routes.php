<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('categories', '\Alomgyar\Categories\CategoryController')->middleware(['can:categories'])->except(['destroy']);
            //Route::get('categories', '\Alomgyar\Categories\CategoryController@index')->name('categories.index')->middleware(['can:categories.index']);
            //Route::get('categories/show', '\Alomgyar\Categories\CategoryController@show')->name('categories.show')->middleware(['can:categories.show']);
            //Route::resource('categories', '\Alomgyar\Categories\CategoryController')->only(['create','store', 'edit', 'update'])->middleware(['can:categories.storing']);
            Route::delete('categories/destroy', '\Alomgyar\Categories\CategoryController@destroy')->name('categories.destroy')->middleware(['can:categories.destroy']);
        });
    });
});
