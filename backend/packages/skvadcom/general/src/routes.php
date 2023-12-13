<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::post('general/createrow', '\Skvadcom\General\GeneralController@createrow')->name('createrow');
            Route::put('general/updaterow', '\Skvadcom\General\GeneralController@updaterow')->name('updaterow');
            Route::delete('general/deleterow', '\Skvadcom\General\GeneralController@deleterow')->name('deleterow');
            Route::resource('general', '\Skvadcom\General\GeneralController');
        });
    });
});
