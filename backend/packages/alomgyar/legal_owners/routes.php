<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('legal_owners/search', '\Alomgyar\Legal_owners\LegalOwnerController@search')->name('legal_owners.search');
            Route::resource('legal_owners', '\Alomgyar\Legal_owners\LegalOwnerController')->middleware(['can:legal_owners.index']);
        });
    });
});
