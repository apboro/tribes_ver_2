<?php

use App\Http\Controllers\Manager\AdminFeedbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Manager Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware(['auth:sanctum', 'admin'])->namespace('App\Http\Controllers\Manager')->group(function() {
    Route::post('/users', 'UserController@list')->name('manager.users.list');
    Route::post('/users-export', 'UserController@export')->name('manager.users.export');
    Route::post('/user', 'UserController@get')->name('manager.user.get');
    Route::post('/auth', 'UserController@auth')->name('manager.users.self');
    Route::post('/user/appoint-admin', 'UserController@appointAdmin')->name('manager.users.appoint-admin');
    Route::post('/user/commission', 'UserController@commission')->name('manager.users.commission');
    //Payment
    Route::post('/payments', 'PaymentController@list')->name('manager.payments.list');
    Route::post('/customers', 'PaymentController@customers')->name('manager.customers.list');
    //Community
    Route::post('/communities', 'CommunityController@list')->name('manager.community.list');
    Route::post('/community', 'CommunityController@get')->name('manager.community.get');
    //Feedback
    Route::get('/feedback/list', [AdminFeedbackController::class, 'list'])->name('manager.feedback.list');
    Route::get('/feedback/{feedback}', [AdminFeedbackController::class, 'get'])->name('manager.feedback.get');
    Route::post('/feedback/answer', [AdminFeedbackController::class, 'answer'])->name('manager.feedback.answer');
    Route::post('/feedback/close/{feedback}', [AdminFeedbackController::class, 'close'])->name('manager.feedback.close');

});