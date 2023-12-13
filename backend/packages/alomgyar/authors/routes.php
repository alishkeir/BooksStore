<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Alomgyar\Authors\AuthorController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('authors/search', [AuthorController::class, 'search'])->name('authors.search')->middleware(['can:authors.index']);
            Route::resource('authors', AuthorController::class)->middleware(['can:authors']);
        });
    });
});
