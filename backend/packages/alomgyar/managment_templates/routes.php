<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            //Route::resource('managment_templates', '\Alomgyar\Managment_templates\Managment_templateController')->middleware(['can:managment_templates.index']);
            //Route::get('managment_templates', '\Alomgyar\Managment_templates\Managment_templateController@index')->name('managment_templates.index')->middleware(['can:managment_templates.index']);
            //Route::get('managment_templates/show', '\Alomgyar\Managment_templates\Managment_templateController@show')->name('managment_templates.show')->middleware(['can:managment_templates.show']);
            //Route::resource('managment_templates', '\Alomgyar\Managment_templates\Managment_templateController')->only(['create','store', 'edit', 'update'])->middleware(['can:managment_templates.storing']);
            /*
            Route::get('managment_templates/index', '\Alomgyar\Managment_templates\Managment_templateController@index')->name('managment_templates.index')->middleware(['can:managment_templates.index']);
            Route::get('managment_templates/orders', '\Alomgyar\Managment_templates\Managment_templateController@orders')->name('managment_templates.orders')->middleware(['can:managment_templates.orders']);
            Route::get('managment_templates/order', '\Alomgyar\Managment_templates\Managment_templateController@order')->name('managment_templates.order')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/stock', '\Alomgyar\Managment_templates\Managment_templateController@stock')->name('managment_templates.stock-product')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/stock-history', '\Alomgyar\Managment_templates\Managment_templateController@stockhistory')->name('managment_templates.stock-history')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/stock-suppliers', '\Alomgyar\Managment_templates\Managment_templateController@stocksupplier')->name('managment_templates.stock-supplier')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/stock-warehouse', '\Alomgyar\Managment_templates\Managment_templateController@stockwarehouse')->name('managment_templates.stock-warehouse')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/stock-in', '\Alomgyar\Managment_templates\Managment_templateController@stockin')->name('managment_templates.stock-in')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/stock-out', '\Alomgyar\Managment_templates\Managment_templateController@stockout')->name('managment_templates.stock-out')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/sales-riport', '\Alomgyar\Managment_templates\Managment_templateController@salesriport')->name('managment_templates.salesriport')->middleware(['can:managment_templates.order']);
            Route::get('managment_templates/warehouse', '\Alomgyar\Managment_templates\Managment_templateController@warehouse')->name('managment_templates.warehouse')->middleware(['can:managment_templates.order']);
            */
        });
    });
});
