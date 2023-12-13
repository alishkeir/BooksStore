<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('templates', \Alomgyar\Templates\TemplatesController::class)->middleware(['can:templates.index']);
            //Route::get('templates', '\Alomgyar\Templates\TemplatesController@index')->name('templates.index')->middleware(['can:templates.index']);
            //Route::get('templates/show', '\Alomgyar\Templates\TemplatesController@show')->name('templates.show')->middleware(['can:templates.show']);
            //Route::resource('templates', '\Alomgyar\Templates\TemplatesController')->only(['create','store', 'edit', 'update'])->middleware(['can:templates.storing']);
            //Route::delete('templates/destroy', '\Alomgyar\Templates\TemplatesController@destroy')->name('templates.destroy')->middleware(['can:templates.destroy']);
        });
    });
});
