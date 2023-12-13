<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('writers/search', '\Alomgyar\Writers\WriterController@search')->name('writers.search');
            Route::resource('writers', '\Alomgyar\Writers\WriterController')->except(['show'])->middleware(['can:writers.index']);
            Route::get('writers/{writer}', '\Alomgyar\Writers\WriterController@show')->name('writers.show')->middleware(['can:writers.show']);
        });
    });
});
