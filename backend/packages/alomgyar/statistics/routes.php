<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('statistics', '\Alomgyar\Statistics\StatisticsController')->middleware(['can:statistics.index']);
            Route::post('statistics/generate-traffic', '\Alomgyar\Statistics\StatisticsController@generateTraffic')->name('statistics.generate');
            Route::post('statistics/generate-products', '\Alomgyar\Statistics\StatisticsController@generateProducts')->name('statistics.generate-products');
        });
    });
});
