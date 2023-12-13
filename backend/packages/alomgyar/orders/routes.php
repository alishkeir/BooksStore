<?php

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('orders/ok', '\Alomgyar\Orders\OrderController@orderItemsOk')->name('orders.orderItemsOk')->middleware(['can:orders.index']);
            Route::get('orders/no', '\Alomgyar\Orders\OrderController@orderItemsNo')->name('orders.orderItemsNo')->middleware(['can:orders.index']);
            Route::get('orders/almost', '\Alomgyar\Orders\OrderController@orderItemsAlmost')->name('orders.orderItemsAlmost')->middleware(['can:orders.index']);
            Route::get('orders/items', '\Alomgyar\Orders\OrderController@orderItems')->name('orders.orderItems')->middleware(['can:orders.index']);
            Route::get('orders/pickupoints', '\Alomgyar\Orders\OrderController@searchPickupPoint')->name('orders.points.search')->middleware(['can:orders.index']);


            Route::resource('orders', '\Alomgyar\Orders\OrderController')->middleware(['can:orders.index']);
            Route::post('orders/delete', '\Alomgyar\Orders\OrderController@deleteOrder')->name('orders.delete')->middleware(['can:orders.delete']);
            Route::post('orders/invoice/storno', '\Alomgyar\Orders\OrderController@storno')->name('orders.invoice.storno')->middleware(['can:orders.storno']);
            Route::get('orders/invoice/get/{id}/{type?}', '\Alomgyar\Orders\OrderController@getinvoicepdf')->name('orders.invoice.get');
            Route::post('orders/setstatus', '\Alomgyar\Orders\OrderController@setstatus')->name('orders.setstatus');
            Route::post('orders/setpaymentstatus', '\Alomgyar\Orders\OrderController@setPaymentStatus')->name('orders.setpaymentstatus');
        });
    });
});
