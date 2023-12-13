<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('posts', '\Alomgyar\Posts\PostController')->middleware(['can:posts.index'])->except(['destroy']);
            //Route::get('posts', '\Alomgyar\Posts\PostController@index')->name('posts.index')->middleware(['can:posts.index']);
            //Route::get('posts/show', '\Alomgyar\Posts\PostController@show')->name('posts.show')->middleware(['can:posts.show']);
            //Route::resource('posts', '\Alomgyar\Posts\PostController')->only(['create','store', 'edit', 'update'])->middleware(['can:posts.storing']);
            Route::delete('posts/destroy', '\Alomgyar\Posts\PostController@destroy')->name('posts.destroy')->middleware(['can:posts.destroy']);
        });
    });
});
