<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('metadata', '\Alomgyar\Settings\SettingsMetaDataController')->middleware(['can:settings.index']);
            Route::resource('metadata', '\Alomgyar\Settings\SettingsMetaDataController')->only(['create', 'store', 'edit', 'update'])->middleware(['can:settings.storing']);
            //Route::get('settings', '\Alomgyar\Settings\SettingsController@index')->name('settings.index')->middleware(['can:settings.index']);
            //Route::get('settings/show', '\Alomgyar\Settings\SettingsController@show')->name('settings.show')->middleware(['can:settings.show']);

            Route::resource('settings', '\Alomgyar\Settings\SettingsController')->middleware(['can:settings.index']);
            Route::resource('settings', '\Alomgyar\Settings\SettingsController')->only(['create', 'store', 'edit', 'update'])->middleware(['can:settings.storing']);
            //Route::delete('settings/destroy', '\Alomgyar\Settings\SettingsController@destroy')->name('settings.destroy')->middleware(['can:settings.destroy']);
        });
    });
});
