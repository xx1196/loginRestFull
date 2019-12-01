<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'guest'], function () {
    Route::post('login', 'API\AuthController@login');
    Route::post('register', 'API\AuthController@register');
    Route::post('qr-generate', 'API\QrController@generate');
    Route::get('users/verify/{token}', 'API\UserController@verify')->name('verify');
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'API\AuthController@logout');

    Route::resource('users', 'API\UserController', [
            'except' => ['create', 'edit']
        ]
    );

    Route::delete('users/deactivated/{user}', 'API\UserController@deactivated');

    Route::delete('users/activated/{user}', 'API\UserController@activated');

    Route::get('deactivatedUsers', 'API\UserController@deactivatedUsers');

    Route::get('users/{user}/resend', 'API\UserController@resend')->name('resend');
});
