<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::delete('carousels/destroy', '\Alomgyar\Carousels\CarouselController@destroy')->name('carousels.destroy')->middleware(['can:carousels.destroy']);
            Route::resource('carousels', '\Alomgyar\Carousels\CarouselController')->middleware(['can:carousels.index'])->except(['destroy']);
        });
    });
});
