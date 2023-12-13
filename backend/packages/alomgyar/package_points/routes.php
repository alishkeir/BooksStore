<?php

use Alomgyar\PackagePoints\Controllers\Packages\CreateController as PackagePointCreateController;
use Alomgyar\PackagePoints\Controllers\Packages\DeleteController as PackagePointDeleteController;
use Alomgyar\PackagePoints\Controllers\Packages\EditController as PackagePointEditController;
use Alomgyar\PackagePoints\Controllers\Packages\ListController as PackagePointListController;
use Alomgyar\PackagePoints\Controllers\Packages\StoreController as PackagePointStoreController;
use Alomgyar\PackagePoints\Controllers\Packages\UpdateController as PackagePointUpdateController;
use Alomgyar\PackagePoints\Controllers\Partners\CreateController;
use Alomgyar\PackagePoints\Controllers\Partners\DeleteController;
use Alomgyar\PackagePoints\Controllers\Partners\EditController;
use Alomgyar\PackagePoints\Controllers\Partners\ListController;
use Alomgyar\PackagePoints\Controllers\Partners\StoreController;
use Alomgyar\PackagePoints\Controllers\Partners\UpdateController;
use Alomgyar\PackagePoints\Controllers\Shops\CreateController as ShopCreateController;
use Alomgyar\PackagePoints\Controllers\Shops\DeleteController as ShopDeleteController;
use Alomgyar\PackagePoints\Controllers\Shops\EditController as ShopEditController;
use Alomgyar\PackagePoints\Controllers\Shops\ListController as ShopListController;
use Alomgyar\PackagePoints\Controllers\Shops\StoreController as ShopStoreController;
use Alomgyar\PackagePoints\Controllers\Shops\UpdateController as ShopUpdateController;

Route::middleware('web')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::prefix('gephaz')->group(function () {
            Route::prefix('package-points')->as('package-points.')->group(function () {
                Route::as('package.')->group(function () {
                    Route::get('/', PackagePointListController::class)->name('list')->middleware(['can:package-points.package.list']);
                    Route::get('/create', PackagePointCreateController::class)->name('create');
                    Route::post('/create', PackagePointStoreController::class)->name('store');
                    Route::get('/{package}/edit', PackagePointEditController::class)->name('edit');
                    Route::put('/{package}/edit', PackagePointUpdateController::class)->name('update');
                    Route::delete('/{package}/delete', PackagePointDeleteController::class)->name('delete');
                });

                Route::prefix('partners')->as('partners.')->group(function () {
                    Route::get('/', ListController::class)->name('list')->middleware(['can:package-points.partners.list']);
                    Route::get('/create', CreateController::class)->name('create');
                    Route::post('/create', StoreController::class)->name('store');
                    Route::get('/{partner}/edit', EditController::class)->name('edit');
                    Route::put('/{partner}/edit', UpdateController::class)->name('update');
                    Route::delete('/{partner}/delete', DeleteController::class)->name('delete');
                });

                Route::prefix('partners')->as('partners.')->group(function () {
                    Route::get('/', ListController::class)->name('list')->middleware(['can:package-points.partners.list']);
                    Route::get('/create', CreateController::class)->name('create');
                    Route::post('/create', StoreController::class)->name('store');
                    Route::get('/{partner}/edit', EditController::class)->name('edit');
                    Route::put('/{partner}/edit', UpdateController::class)->name('update');
                    Route::delete('/{partner}/delete', DeleteController::class)->name('delete');
                });

                Route::prefix('shops')->as('shops.')->group(function () {
                    Route::get('/', ShopListController::class)->name('list')->middleware(['can:package-points.shops.list']);
                    Route::get('/create', ShopCreateController::class)->name('create');
                    Route::post('/create', ShopStoreController::class)->name('store');
                    Route::get('/{shop}/edit', ShopEditController::class)->name('edit');
                    Route::put('/{shop}/edit', ShopUpdateController::class)->name('update');
                    Route::delete('/{shop}/delete', ShopDeleteController::class)->name('delete');
                });
            });
        });
    });
});
