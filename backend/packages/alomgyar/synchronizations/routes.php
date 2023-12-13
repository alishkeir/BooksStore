<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            //Route::resource('synchronizations', '\Alomgyar\Synchronizations\SynchronizationController')->except(['destroy']);
            //Route::get('synchronizations', '\Alomgyar\Synchronizations\SynchronizationController@index')->name('synchronizations.index')->middleware(['can:synchronizations.index']);
            //Route::get('synchronizations/show', '\Alomgyar\Synchronizations\SynchronizationController@show')->name('synchronizations.show')->middleware(['can:synchronizations.show']);
            //Route::resource('synchronizations', '\Alomgyar\Synchronizations\SynchronizationController')->only(['create','store', 'edit', 'update'])->middleware(['can:synchronizations.storing']);
            //Route::delete('synchronizations/destroy', '\Alomgyar\Synchronizations\SynchronizationController@destroy')->name('synchronizations.destroy');
        });
    });
});
