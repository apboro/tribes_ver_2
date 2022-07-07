<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers')->group(function() {
    Route::post('/session/put', 'Controller@sessionPut')->name('session.put');
    Route::get('/session/get', 'Controller@sessionGet')->name('session.get');
    Route::post('/session/flush', 'Controller@sessionFlush')->name('session.flush');
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\API')->group(function() {
    Route::post('/payment/addCard', 'PaymentController@addCard')->name('api.payment.card.add'); // Имя Роута было Занято. Добавил в начало 'api'.
    Route::post('/payment/removeCard', 'PaymentController@removeCard')->name('payment.card.remove');
    Route::post('/payment/cardList', 'PaymentController@cardList')->name('api.payment.card.list'); // Имя Роута было Занято. Добавил в начало 'api'. 
    Route::post('/payment/init', 'PaymentController@init')->name('api.payment.init'); // Имя Роута было Занято. Добавил в начало 'api'. 
    Route::post('/payment/payout', 'PaymentController@payout')->name('payment.init');
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\API')->group(function() {

    Route::post('/file/upload','FileController@upload');
    Route::post('/file/get','FileController@get');
    Route::post('/file/delete','FileController@delete');


    Route::post('/video/upload','VideoController@upload');

    Route::get('/lesson/templates', 'LessonController@templateList')->name('template.list');

    Route::post('/lesson/store', 'LessonController@store')->name('lesson.store');
    Route::post('/lesson/edit', 'LessonController@edit')->name('lesson.edit');
    Route::post('/lesson/delete', 'LessonController@delete')->name('lesson.delete');

    Route::get('/course/list', 'CourseController@courseList')->name('api.course.list'); // Имя Роута было Занято. Добавил в начало 'api'.
    Route::post('/course/edit', 'CourseController@edit')->name('api.course.edit'); // Имя Роута было Занято. Добавил в начало 'api'.

    Route::post('/course/store', 'CourseController@store')->name('course.store');
    Route::post('/course/delete', 'CourseController@delete')->name('course.delete');

    /** Получение количества оплаченных тарифов за определенный период времени. Время в формате Y-m-d  */
    Route::get('tariff/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getTotalTariff')->name('get.totall.tariff');

    /** Получение количества донатов за определенный период времени. Время в формате Y-m-d  */
    Route::get('donate/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getTotalDonate')->name('get.totall.donate');

    /** Получение суммы тарифов за определенный период времени. Время в формате Y-m-d*/
    Route::get('sum/tariff/{community}', 'StatisticController@getSumTariff')->name('get.sum.tariff');

    /** Получение суммы донатов за определенный период времени. Время в формате Y-m-d*/
    Route::get('sum/donate/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getSumDonate')->name('get.sum.donate');

    /** Получение уникальных посетителей платёжной страницы за период времени в формате Y-m-d*/
    Route::get('hosts/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getHostsPeriod')->name('get.hosts');

    Route::group(['prefix' => 'questions'], function () {
        Route::post('list', 'QuestionController@list')->name('question.list');
        Route::post('get', 'QuestionController@get')->name('question.get');
        Route::post('add', 'QuestionController@add')->name('question.add');
        Route::post('store', 'QuestionController@store')->name('question.store');
        Route::post('delete', 'QuestionController@delete')->name('question.delete');
        Route::post('do', 'QuestionController@do')->name('question.do');
    });
    Route::group(['prefix' => 'communities'], function () {
        Route::post('list', 'CommunityController@list')->name('community.list.api');
        Route::post('get', 'CommunityController@get')->name('community.get');
        /*Route::post('add', 'CommunityController@add')->name('community.add');
        Route::post('store', 'CommunityController@store')->name('community.store');
        Route::post('delete', 'CommunityController@delete')->name('community.delete');*/
    });
});