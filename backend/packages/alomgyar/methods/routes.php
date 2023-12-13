<?php

use Alomgyar\Methods\ShippingMethodsController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::resource('methods', '\Alomgyar\Methods\MethodsController')->middleware(['can:methods.index']);

            Route::get('shipping-method/{shippingMethod}/edit', [ShippingMethodsController::class, 'edit'])
                ->middleware(['can:methods.edit'])
                ->name('shipping-method.edit');

            Route::put('shipping-method/{shippingMethod}/edit', [ShippingMethodsController::class, 'update'])
                ->middleware(['can:methods.edit'])
                ->name('shipping-method.update');
        });
    });
});
