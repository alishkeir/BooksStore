<?php

Route::middleware('auth')->group(function () {
    Route::prefix('gephaz')->group(function () {
        Route::get('/', 'AdminController@index')->name('admin');

        Route::resource('user', 'UserController');

        Route::resources([
            'packages' => 'PackageController',
        ]);

        Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs.index');

        Route::post('fileupload', 'FileController@upload')->name('fileupload');

        Route::get('storage/{folder}/{file}', function ($folder, $file) {
            $path = storage_path('app'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$file);

            return response()->file($path);
        });
    });
});
