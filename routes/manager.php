<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Manager Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\Manager')->group(function() {
    Route::post('/users', 'UserController@list')->name('manager.users.list');
    Route::post('/auth', 'UserController@auth')->name('manager.users.self');

    //Payment
    Route::post('/payments', 'PaymentController@list')->name('manager.payments.list');
    Route::post('/customers', 'PaymentController@customers')->name('manager.customers.list');
    //Community
    Route::post('/communities', 'CommunityController@list')->name('manager.community.list');
    Route::post('/community', 'CommunityController@get')->name('manager.community.get');
});