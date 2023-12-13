<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('comments', '\Alomgyar\Comments\CommentController')->middleware(['can:comments.index']);
            //Route::delete('comments/destroy', '\Alomgyar\Comments\CommentController@destroy')->name('comments.destroy')->middleware(['can:comments.destroy']);
        });
    });
});
