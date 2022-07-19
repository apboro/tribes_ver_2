<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Manager Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\Manager')->group(function() {
    Route::post('/users', 'UserController@list')->name('users.list');
    Route::post('/auth', 'UserController@auth')->name('users.self');

    //Payment
    Route::post('/payments', 'PaymentController@list')->name('payments.list');

    //Community
    Route::post('/communities', 'CommunityController@list')->name('community.list');
    Route::post('/community', 'CommunityController@get')->name('community.get');
});