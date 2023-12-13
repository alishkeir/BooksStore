<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('publishers/search', '\Alomgyar\Publishers\PublisherController@search')->name('publishers.search');

            Route::resource('publishers', '\Alomgyar\Publishers\PublisherController')->middleware(['can:publishers.index'])->except(['destroy']);
            //Route::get('publishers', '\Alomgyar\Publishers\PublisherController@index')->name('publishers.index')->middleware(['can:publishers.index']);
            //Route::get('publishers/show', '\Alomgyar\Publishers\PublisherController@show')->name('publishers.show')->middleware(['can:publishers.show']);
            //Route::resource('publishers', '\Alomgyar\Publishers\PublisherController')->only(['create','store', 'edit', 'update'])->middleware(['can:publishers.storing']);
            Route::delete('publishers/destroy', '\Alomgyar\Publishers\PublisherController@destroy')->name('publishers.destroy')->middleware(['can:publishers.destroy']);
        });
    });
});
