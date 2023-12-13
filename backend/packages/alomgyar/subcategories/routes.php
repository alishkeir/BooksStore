<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::delete('subcategories/destroy', '\Alomgyar\Subcategories\SubcategoryController@destroy')->name('subcategories.destroy')->middleware(['can:subcategories.destroy']);
            Route::resource('subcategories', '\Alomgyar\Subcategories\SubcategoryController')->middleware(['can:subcategories'])->except(['destroy'])->except(['destroy']);
            //Route::get('subcategories', '\Alomgyar\Subcategories\SubcategoryController@index')->name('subcategories.index')->middleware(['can:subcategories.index']);
            //Route::get('subcategories/show', '\Alomgyar\Subcategories\SubcategoryController@show')->name('subcategories.show')->middleware(['can:subcategories.show']);
            //Route::resource('subcategories', '\Alomgyar\Subcategories\SubcategoryController')->only(['create','store', 'edit', 'update'])->middleware(['can:subcategories.storing']);
        });
    });
});
