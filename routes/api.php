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
Route::namespace('App\Http\Controllers\API')->group(function() {
    Route::post('/login', 'AuthController@login')->name('auth.login');
});

Route::namespace('App\Http\Controllers\API')->group(function() {
    Route::post('/login-as-admin', 'AuthController@loginAsAdmin')->name('auth.login_as_admin');
});

Route::namespace('App\Http\Controllers\API')->middleware(['auth:sanctum', 'admin'])->group(function() {
    Route::post('/login-as', 'AuthController@loginAs')->name('auth.login_as');
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers')->group(function() {
    Route::post('/session/put', 'Controller@sessionPut')->name('api.session.put');
    Route::get('/session/get', 'Controller@sessionGet')->name('api.session.get');
    Route::post('/session/flush', 'Controller@sessionFlush')->name('api.session.flush');
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\API')->group(function() {
    Route::post('/payment/addCard', 'PaymentController@addCard')->name('api.payment.card.add'); // Имя Роута было Занято. Добавил в начало 'api'.
    Route::post('/payment/removeCard', 'PaymentController@removeCard')->name('payment.card.remove');
    Route::post('/payment/cardList', 'PaymentController@cardList')->name('api.payment.card.list'); // Имя Роута было Занято. Добавил в начало 'api'. 
    Route::post('/payment/init', 'PaymentController@init')->name('api.payment.init'); // Имя Роута было Занято. Добавил в начало 'api'. 
    Route::post('/payment/payout', 'PaymentController@payout')->name('api.payment.payout');
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\API')->group(function() {

    Route::post('/file/upload','FileController@upload');
    Route::post('/file/get','FileController@get');
    Route::post('/file/delete','FileController@delete');


    Route::post('/video/upload','VideoController@upload');

    Route::get('/lesson/templates', 'LessonController@templateList')->name('api.template.list');

    Route::post('/lesson/store', 'LessonController@store')->name('api.lesson.store');
    Route::post('/lesson/edit', 'LessonController@edit')->name('api.lesson.edit');
    Route::post('/lesson/delete', 'LessonController@delete')->name('api.lesson.delete');

    Route::get('/course/list', 'CourseController@courseList')->name('api.course.list'); // Имя Роута было Занято. Добавил в начало 'api'.
    Route::post('/course/edit', 'CourseController@edit')->name('api.course.edit'); // Имя Роута было Занято. Добавил в начало 'api'.

    Route::post('/course/store', 'CourseController@store')->name('api.course.store');
    Route::post('/course/delete', 'CourseController@delete')->name('api.course.delete');

    /** Получение количества оплаченных тарифов за определенный период времени. Время в формате Y-m-d  */
    Route::get('tariff/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getTotalTariff')->name('api.get.totall.tariff');

    /** Получение количества донатов за определенный период времени. Время в формате Y-m-d  */
    Route::get('donate/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getTotalDonate')->name('api.get.totall.donate');

    /** Получение суммы тарифов за определенный период времени. Время в формате Y-m-d*/
    Route::get('sum/tariff/{community}', 'StatisticController@getSumTariff')->name('get.sum.tariff');

    /** Получение суммы донатов за определенный период времени. Время в формате Y-m-d*/
    Route::get('sum/donate/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getSumDonate')->name('api.get.sum.donate');

    /** Получение уникальных посетителей платёжной страницы за период времени в формате Y-m-d*/
    Route::get('hosts/{community}/{count}/{rank}/{beforeTime?}', 'StatisticController@getHostsPeriod')->name('api.get.hosts');

    Route::group(['prefix' => 'questions'], function () {
        Route::post('list', 'QuestionController@list')->name('api.question.list');
        Route::post('get', 'QuestionController@get')->name('api.question.get');
        Route::post('add', 'QuestionController@add')->name('api.question.add');
        Route::post('store', 'QuestionController@store')->name('api.question.store');
        Route::post('delete', 'QuestionController@delete')->name('api.question.delete');
        Route::post('do', 'QuestionController@do')->name('api.question.do');
    });
    Route::group(['prefix' => 'communities'], function () {
        Route::post('list', 'CommunityController@list')->name('api.community.list');
        Route::post('get', 'CommunityController@get')->name('api.community.get');
        /*Route::post('add', 'CommunityController@add')->name('api.community.add');
        Route::post('store', 'CommunityController@store')->name('api.community.store');
        Route::post('delete', 'CommunityController@delete')->name('api.community.delete');*/
    });

    Route::group(['prefix' => 'media-statistic'], function () {
        Route::post('sales-list', 'MediaStatisticController@salesList')->name('api.media-statistic.sales-list');
        Route::post('products-list', 'MediaStatisticController@productsList')->name('api.media-statistic.products-list');
        Route::post('views-list', 'MediaStatisticController@viewsList')->name('api.media-statistic.views-list');
    });
});