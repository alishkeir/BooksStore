<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('countries', '\Alomgyar\Countries\CountryController')->middleware(['can:countries.index'])->except(['destroy']);
            //Route::get('countries', '\Alomgyar\Countries\CountryController@index')->name('countries.index')->middleware(['can:countries.index']);
            //Route::get('countries/show', '\Alomgyar\Countries\CountryController@show')->name('countries.show')->middleware(['can:countries.show']);
            //Route::resource('countries', '\Alomgyar\Countries\CountryController')->only(['create','store', 'edit', 'update'])->middleware(['can:countries.storing']);
            Route::delete('countries/destroy', '\Alomgyar\Countries\CountryController@destroy')->name('countries.destroy')->middleware(['can:countries.destroy']);
        });
    });
});
