<?php

use App\Http\Controllers\CustomerApiController;
use Illuminate\Support\Facades\Artisan;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::get('products/flash-promotion', '\Alomgyar\Products\ProductController@flashPromotion')->name('products.flash-promotion')->middleware(['can:products.flash-promotion']);

            Route::get('products/export', '\Alomgyar\Products\ProductController@export')->name('products.export')->middleware(['can:products.export']);
            Route::get('products/import', '\Alomgyar\Products\ProductController@import')->name('products.import')->middleware(['can:products.import']);
            Route::put('products/import', '\Alomgyar\Products\ProductController@importproduct')->name('process-import');
            Route::post('products/import', '\Alomgyar\Products\ProductController@runimport')->name('run-import');
            Route::post('file-upload', '\Alomgyar\Products\ProductController@fileupload')->name('file-upload');

            Route::get('products/search', '\Alomgyar\Products\ProductController@search')->name('products.search');

            Route::get('products/ranked_list', function () {
                Artisan::call('ranked:determine');
                Artisan::call('cache:clear');
                session()->flash('success', 'Sikerlisták generálása sikeresen megtörtént!');

                return redirect()->back();
            })->name('products.ranked_list');
            Route::get('products/calculate_price', function () {
                Artisan::call('calculate:prices');
                session()->flash('success', 'Árak újrakalkulálása megtörtént!');

                return redirect()->back();
            })->name('products.calculate_price');

            Route::get('product/download', [CustomerApiController::class, 'getDownloadEbook'])->name('product.download');

            Route::resource('products', '\Alomgyar\Products\ProductController')->middleware(['can:products.index'])->except(['destroy']);
            Route::delete('products/destroy', '\Alomgyar\Products\ProductController@destroy')->name('products.destroy')->middleware(['can:products.destroy']);
        });
    });
});
