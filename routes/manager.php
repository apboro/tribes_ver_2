<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Manager Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware('auth:api')->namespace('App\Http\Controllers\Manager')->group(function() {
    Route::post('/users', 'UserController@list')->name('users.list');
    Route::post('/auth', 'UserController@auth')->name('users.self');
});
