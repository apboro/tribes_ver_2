<?php


use App\Http\Controllers\APIv3\Admin\AdminAuthController;
use App\Http\Controllers\APIv3\User\ApiForgotPasswordController;
use App\Http\Controllers\APIv3\User\ApiPhoneController;
use App\Http\Controllers\APIv3\User\ApiRegisterController;
use App\Http\Controllers\APIv3\User\ApiAuthController;

use App\Http\Controllers\APIv3\User\ApiResetPasswordController;
use App\Http\Controllers\APIv3\User\ApiUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
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
Route::prefix('api/v3')->group(function () {
    Route::post('/user/register', [ApiRegisterController::class,'register'])->name('auth.register');
    Route::post('/user/login', [ApiAuthController::class,'login'])->name('auth.login');
    Route::post('/forgot-password', [ApiForgotPasswordController::class,'sendPasswordResetLink'])->name('password.forgot');
    Route::post('/reset-password', [ApiResetPasswordController::class,'resetUserPassword'])->name('password.reset');
});

Route::prefix('api/v3')->middleware(['api','auth:sanctum'])->group(function () {
    Route::get('/user/logout', [ApiAuthController::class,'logout'])->name('auth.logout');
    Route::post('/user/change-password', [ApiUserController::class,'passChange'])->name('user.change_password');
    Route::get('/get-user-data', [ApiUserController::class,'show'])->name('user.show');
    Route::get('/phone/reset-confirmed', [ApiPhoneController::class,'resetConfirmed'])->name('phone.reset_confirmed');
    Route::post('/phone/send-confirm-code', [ApiPhoneController::class,'sendConfirmCode'])->name('phone.send_confirm_code');
    Route::post('/phone/confirm', [ApiPhoneController::class,'confirmPhone'])->name('phone.confirm');
});

/*
 * old
 */

/*
Route::prefix('api/v3')->middleware(['api','auth:sanctum'])->group(function () {
    Route::get('/user/register',          [RegisterController::class,      'register']          )->name('auth.register');
    Route::post('/user/login',            [AuthController::class,          'login']             )->name('auth.login');
    Route::post('/user/logout',           [AuthController::class,          'logout']            )->name('auth.logout');
    Route::post('/user/password/change',  [AuthController::class,          'passChange']        )->name('profile.password.change');
    Route::post('/user/password/reset',   [ForgotPasswordController::class,'sendResetLinkEmail'])->name('auth.password.reset');
});
*/
Route::prefix('api/v3')->middleware(['api','auth:sanctum'])->namespace('App\Http\Controllers\APIv3\Admin')->group(function () {
    Route::post('/admin/login-as',       [AdminAuthController::class,       'loginAs'          ])->name('auth.login_as')->middleware('admin');
    Route::post('/admin/login-back',     [AdminAuthController::class,       'loginBack'        ])->name('auth.login_back');
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers')->group(function () {
    Route::post('/session/put', 'Controller@sessionPut')->name('api.session.put');
    Route::get('/session/get', 'Controller@sessionGet')->name('api.session.get');
    Route::post('/session/flush', 'Controller@sessionFlush')->name('api.session.flush');
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\API')->group(function () {
    Route::post('/payment/addCard', 'PaymentController@addCard')->name('api.payment.card.add'); // Имя Роута было Занято. Добавил в начало 'api'.
    Route::post('/payment/removeCard', 'PaymentController@removeCard')->name('payment.card.remove');
    Route::post('/payment/cardList', 'PaymentController@cardList')->name('api.payment.card.list'); // Имя Роута было Занято. Добавил в начало 'api'. 
    Route::post('/payment/init', 'PaymentController@init')->name('api.payment.init'); // Имя Роута было Занято. Добавил в начало 'api'. 
    Route::post('/payment/payout', 'PaymentController@payout')->name('api.payment.payout');
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers\API')->group(function () {

    Route::post('/test-tariff', 'TestTariffController@test');
    Route::post('/file/upload', 'FileController@upload');
    Route::post('/file/get', 'FileController@get');
    Route::post('/file/delete', 'FileController@delete');


    Route::post('/video/upload', 'VideoController@upload');

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

    Route::group(['prefix' => 'projects'], function () {
        Route::post('list', 'ProjectController@list')->name('api.project.list');
        Route::post('get', 'ProjectController@get')->name('api.project.get');
        Route::post('add', 'ProjectController@add')->name('api.project.add');
        Route::post('store', 'ProjectController@store')->name('api.project.store');
        Route::post('delete', 'ProjectController@delete')->name('api.project.delete');
        Route::post('attach-communities', 'ProjectController@attachCommunities')->name('api.project.attach-communities');
    });

    Route::group(['prefix' => 'media-statistic'], function () {
        Route::post('sales-list', 'MediaStatisticController@salesList')->name('api.media-statistic.sales-list');
        Route::post('products-list', 'MediaStatisticController@productsList')->name('api.media-statistic.products-list');
        Route::post('views-list', 'MediaStatisticController@viewsList')->name('api.media-statistic.views-list');
    });

    Route::group(['prefix' => 'tele-statistic'], function () {
        Route::post('members', 'TeleDialogStatisticController@members')->name('api.tele-statistic.members');
        Route::post('member-charts', 'TeleDialogStatisticController@memberCharts')->name('api.tele-statistic.member-charts');

        Route::post('payments-list', 'FinanceStatisticController@paymentsList')->name('api.tele-statistic.payments-list');
        Route::post('payments-charts', 'FinanceStatisticController@paymentsCharts')->name('api.tele-statistic.payments-charts');

        Route::post('messages', 'TeleMessageStatisticController@messages')->name('api.tele-statistic.messages');
        Route::post('message-charts', 'TeleMessageStatisticController@messageCharts')->name('api.tele-statistic.message-charts');

        Route::post('export-members', 'TeleDialogStatisticController@exportMembers')->name('api.tele-statistic.export-members');
        Route::post('export-messages', 'TeleMessageStatisticController@exportMessages')->name('api.tele-statistic.export-messages');
        Route::post('export-payments', 'FinanceStatisticController@exportPayments')->name('api.tele-statistic.export-payments');


    });

});