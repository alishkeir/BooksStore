<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('recommenders', '\Alomgyar\Recommenders\RecommendersController')->middleware(['can:recommenders.index'])->except(['destroy']);
            Route::delete('recommenders/destroy', '\Alomgyar\Recommenders\RecommendersController@destroy')->name('recommenders.destroy')->middleware(['can:recommenders.destroy']);
        });
    });
});
