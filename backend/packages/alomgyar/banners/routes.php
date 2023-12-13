<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Alomgyar\Banners\Controllers\BannersController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('banners', BannersController::class)->middleware(['can:banners.index'])->name('banners');
        });
    });
});
