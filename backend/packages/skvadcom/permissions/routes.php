<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            //Route::resource('permissions', '\Skvadcom\Permissions\PermissionController')->middleware(['can:permissions']);
            Route::get('permissions', '\Skvadcom\Permissions\PermissionController@index')->name('permissions.index')->middleware(['can:permissions.index']);
            Route::get('permissions/show', '\Skvadcom\Permissions\PermissionController@show')->name('permissions.show')->middleware(['can:permissions.show']);
            Route::resource('permissions', '\Skvadcom\Permissions\PermissionController')->only(['create', 'store', 'edit', 'update'])->middleware(['can:permissions.storing'])->except(['destroy']);
            Route::delete('permissions/destroy', '\Skvadcom\Permissions\PermissionController@destroy')->name('permissions.destroy')->middleware(['can:permissions.destroy']);
            Route::resource('role', '\Skvadcom\Permissions\RoleController')->middleware(['can:permissions.roles']);
        });
    });
});
