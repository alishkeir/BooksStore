<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('affiliates', '\Alomgyar\Affiliates\AffiliateController@index')->name('affiliates.index')->middleware(['can:affiliates.index']);
        });
    });
});