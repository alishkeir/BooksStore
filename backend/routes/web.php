<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('reset-password/{token}', '\Laravel\Fortify\Http\Controllers\NewPasswordController@create')->name('password.reset');
/*Route::group(['domain' => env('BACKEND_URL') ?? env('APP_URL')], function(){
    Route::get('email/verify/{id}/{hash}', [\Alomgyar\Customers\CustomerVerifyEmailController::class])->name('verification.verify');
});*/
Route::get('email/verify/{id}/{hash}', [\Alomgyar\Customers\CustomerVerifyEmailController::class, '__invoke'])->name('verification.verify');

Route::get('/', function () {
    //return redirect('gephaz');
});
Route::prefix('gephaz')->group(function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    //Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    //Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::get('/register', function () {
        return redirect('gephaz/login');
    });
});
