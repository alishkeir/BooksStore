<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('coupons', '\Alomgyar\Coupons\CouponController')->middleware(['can:coupons.index']);
            //Route::get('coupons', '\Alomgyar\Coupons\CouponController@index')->name('coupons.index')->middleware(['can:coupons.index']);
            //Route::get('coupons/show', '\Alomgyar\Coupons\CouponController@show')->name('coupons.show')->middleware(['can:coupons.show']);
            //Route::resource('coupons', '\Alomgyar\Coupons\CouponController')->only(['create','store', 'edit', 'update'])->middleware(['can:coupons.storing']);
            Route::delete('coupons/destroy', '\Alomgyar\Coupons\CouponController@destroy')->name('coupons.destroy')->middleware(['can:coupons.destroy']);
        });
    });
});
