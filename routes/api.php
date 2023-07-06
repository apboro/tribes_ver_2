<?php

use App\Http\Controllers\ApiRulesTemplateController;
use App\Http\Controllers\APIv3\ApiFileController;
use App\Http\Controllers\APIv3\ApiProjectController;
use App\Http\Controllers\APIv3\ApiTelegramBotActionController;
use App\Http\Controllers\APIv3\ApiTelegramConnectionController;
use App\Http\Controllers\APIv3\Community\ApiCommunityController;
use App\Http\Controllers\APIv3\Community\ApiCommunityTagController;
use App\Http\Controllers\APIv3\Community\ApiCommunityTelegramUserController;
use App\Http\Controllers\APIv3\Community\ApiTagController;
use App\Http\Controllers\APIv3\Community\Rules\ApiAntispamController;
use App\Http\Controllers\APIv3\Community\Rules\ApiCommunityReputationRulesController;
use App\Http\Controllers\APIv3\Community\Rules\ApiCommunityRuleController;
use App\Http\Controllers\APIv3\Community\Rules\ApiOnboardingController;
use App\Http\Controllers\APIv3\Community\Rules\ApiRankRuleController;
use App\Http\Controllers\APIv3\Community\Rules\ApiUserRulesController;
use App\Http\Controllers\APIv3\Courses\ApiCourseController;
use App\Http\Controllers\APIv3\Donates\ApiNewDonateController;
use App\Http\Controllers\APIv3\Feedback\ApiFeedBackController;
use App\Http\Controllers\APIv3\Knowledge\ApiKnowledgeController;
use App\Http\Controllers\APIv3\Knowledge\ApiQuestionCategoryController;
use App\Http\Controllers\APIv3\Knowledge\ApiQuestionController;
use App\Http\Controllers\APIv3\Manager\ApiAdminCommunityController;
use App\Http\Controllers\APIv3\Manager\ApiAdminFeedBackController;
use App\Http\Controllers\APIv3\Manager\ApiAdminPaymentController;
use App\Http\Controllers\APIv3\Manager\ApiManagerUserController;
use App\Http\Controllers\APIv3\Payments\ApiPaymentCardController;
use App\Http\Controllers\APIv3\Publication\ApiFavouritePublicationController;
use App\Http\Controllers\APIv3\Publication\ApiPublicationController;
use App\Http\Controllers\APIv3\Publication\ApiPublicationPartController;
use App\Http\Controllers\APIv3\Semantic\ApiSemanticController;
use App\Http\Controllers\APIv3\Statistic\ApiExportAllData;
use App\Http\Controllers\APIv3\Statistic\ApiTelegramMessageStatistic;
use App\Http\Controllers\APIv3\Statistic\ApiTelegramModerationStatistic;
use App\Http\Controllers\APIv3\Statistic\ApiTelegramUsersStatistic;
use App\Http\Controllers\APIv3\Subscription\ApiSubscriptionController;
use App\Http\Controllers\APIv3\Subscription\ApiUserSubscriptionController;
use App\Http\Controllers\APIv3\User\ApiAssignDetachTelegramController;
use App\Http\Controllers\APIv3\User\ApiAuthController;
use App\Http\Controllers\APIv3\User\ApiAuthorController;
use App\Http\Controllers\APIv3\User\ApiForgotPasswordController;
use App\Http\Controllers\APIv3\User\ApiMessengersController;
use App\Http\Controllers\APIv3\User\ApiRegisterController;
use App\Http\Controllers\APIv3\User\ApiResetPasswordController;
use App\Http\Controllers\APIv3\User\ApiUserAdditionalFieldsController;
use App\Http\Controllers\APIv3\User\ApiUserController;
use App\Http\Controllers\APIv3\User\ApiUserPhoneController;
use App\Http\Controllers\TelegramUserBotController;
use App\Http\Controllers\TelegramUserReputationController;
use App\Services\SMTP\MailSender;
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
    Route::get('/public/knowledge/{hash}', [ApiKnowledgeController::class, 'public']);
    Route::post('/userbot_session', [TelegramUserBotController::class, 'storeSession']);
    Route::get('/userbot_session', [TelegramUserBotController::class, 'getSession']);
    Route::post('/user/login', [ApiAuthController::class, 'login']);
    Route::post('/user/register', [ApiRegisterController::class, 'register']);
    Route::post('/user/password/forgot', [ApiForgotPasswordController::class, 'sendPasswordResetLink']);
    Route::post('/user/password/reset', [ApiResetPasswordController::class, 'resetUserPassword']);
    Route::post('/courses/pay/{hash}', [ApiCourseController::class, 'pay']);
    Route::get('/courses/show/{hash}', [ApiCourseController::class, 'show_for_all']);
    Route::post('/send_demo_email', [MailSender::class, 'sendDemoEmail']);

    Route::get('/author/{id}', [ApiAuthorController::class, 'showForFollowers']);

    Route::get('/publication/{uuid}', [ApiPublicationController::class, 'showByUuid'])->name('api.publications.show_by_uuid');
});

/** TODO fastFIX  */
//Route::get('/api/v3/question/{id}', [ApiQuestionController::class, 'show']);
Route::prefix('api/v3')->middleware(['api', 'auth_v3:sanctum'])->group(function () {

    Route::get('/user', [ApiUserController::class, 'show']);
    Route::delete('/users', [ApiUserController::class, 'delete'])->name('api.user.delete');
    Route::post('/user/logout', [ApiAuthController::class, 'logout']);
    Route::post('/user/password/change', [ApiUserController::class, 'passChange']);
    Route::put('/users/additional-fields', [ApiUserAdditionalFieldsController::class, 'update'])->name('api.users.edit_additional_fields');

    Route::post('/user/phone/reset-confirmed', [ApiUserPhoneController::class, 'resetConfirmed']);
    Route::post('/user/phone/send-confirm-code', [ApiUserPhoneController::class, 'sendConfirmCode']);
    Route::post('/user/phone/confirm', [ApiUserPhoneController::class, 'confirmPhone']);

    Route::post('/user/telegram/assign', [ApiAssignDetachTelegramController::class, 'assignTelegramAccount']);
    Route::delete('/user/telegram/detach', [ApiAssignDetachTelegramController::class, 'detachTelegramAccount']);
    Route::get('/user/telegram/list', [ApiMessengersController::class, 'list']);
    Route::get('/projects', [ApiProjectController::class, 'index']);
    Route::post('/projects/create', [ApiProjectController::class, 'create']);
    Route::get('/projects/{id}', [ApiProjectController::class, 'show']);
    Route::post('/projects/{id}', [ApiProjectController::class, 'update']);

    Route::get('/user/chats', [ApiCommunityController::class, 'filter']);
    Route::post('/user/chats', [ApiCommunityController::class, 'store']);
    Route::get('/user/chats/{id}', [ApiCommunityController::class, 'show']);

    Route::post('/create_chat/init', [ApiTelegramConnectionController::class, 'create']);
    Route::post('/create_chat/check', [ApiTelegramConnectionController::class, 'checkStatus']);

    Route::post('/feed-back', [ApiFeedBackController::class, 'store']);

    Route::get('/payment-cards', [ApiPaymentCardController::class, 'index']);
    Route::post('/payment-cards', [ApiPaymentCardController::class, 'store']);
    Route::delete('/payment-cards', [ApiPaymentCardController::class, 'delete']);

    Route::get('/courses', [ApiCourseController::class, 'index']);
    Route::get('/courses/{id}', [ApiCourseController::class, 'show']);
    Route::post('/courses', [ApiCourseController::class, 'store']);
    Route::put('/courses/{id}', [ApiCourseController::class, 'update']);
    Route::get('/subscriptions_list', [ApiSubscriptionController::class, 'index']);
    Route::post('/subscription', [ApiSubscriptionController::class, 'show']);
    Route::post('/user/subscription/assign', [ApiUserSubscriptionController::class, 'assignSubscriptionToUser']);
    Route::post('/subscription/pay', [ApiUserSubscriptionController::class, 'payForSubscription']);
    Route::patch('/subscription/recurrent', [ApiUserSubscriptionController::class, 'changeRecurrent']);

    Route::get('/chats/tags', [ApiTagController::class, 'index']);
    Route::get('/chats/tags/{id}', [ApiTagController::class, 'show']);
    Route::delete('/chats/tags/{id}', [ApiTagController::class, 'destroy']);

    Route::post('/chat-tags/attach', [ApiCommunityTagController::class, 'attachTagToChat']);

    Route::get('/user/community-users', [ApiCommunityTelegramUserController::class, 'filter']);
    Route::delete('/user/community-users/detach', [ApiCommunityTelegramUserController::class, 'detachUser']);
    Route::delete('/user/community-users/detach_all', [ApiCommunityTelegramUserController::class, 'detachFromAllCommunities']);
    Route::put('/user/community-users/add_to_list', [ApiCommunityTelegramUserController::class, 'addToList']);
    Route::delete('/user/community-users/remove_from_list', [ApiCommunityTelegramUserController::class, 'removeFromList']);

    Route::get('/user/bot/action-log/filter', [ApiTelegramBotActionController::class, 'filter']);

    Route::post('/user-community-rules', [ApiUserRulesController::class, 'store']);
    Route::get('/user-community-rules', [ApiUserRulesController::class, 'list']);
    Route::get('/user-community-rules/{rule_uuid}', [ApiUserRulesController::class, 'show']);
    Route::put('/user-community-rules', [ApiUserRulesController::class, 'update']);
    Route::delete('/user-community-rules', [ApiUserRulesController::class, 'delete']);
    Route::get('/all_user_rules', [ApiUserRulesController::class, 'getAllRules']);

    Route::post('/antispam', [ApiAntispamController::class, 'store']);
    Route::put('/antispam/{uuid}', [ApiAntispamController::class, 'edit']);
    Route::get('/antispam', [ApiAntispamController::class, 'list']);
    Route::get('/antispam/{uuid}', [ApiAntispamController::class, 'show']);
    Route::delete('/antispam/{antispam_uuid}', [ApiAntispamController::class, 'delete']);

    Route::post('/chats/rules', [ApiCommunityRuleController::class, 'store'])->name('chats.rules.store');
    Route::get('/chats/rules', [ApiCommunityRuleController::class, 'list'])->name('chats.rules.list');
    Route::post('/chats/rules/edit/{uuid}', [ApiCommunityRuleController::class, 'update'])->name('chats.rules.update');
    Route::get('/chats/rules/{uuid}', [ApiCommunityRuleController::class, 'show'])->name('chats.rules.show');
    Route::delete('/chats/rules/{moderation_uuid}', [ApiCommunityRuleController::class, 'delete']);
    Route::get('/chats/rules-template', [ApiRulesTemplateController::class, 'getTemplate']);

    Route::post('/onboarding', [ApiOnboardingController::class, 'store']);
    Route::get('/onboarding', [ApiOnboardingController::class, 'get']);
    Route::get('/onboarding/{onboarding_uuid}', [ApiOnboardingController::class, 'show']);
    Route::post('/onboarding/edit', [ApiOnboardingController::class, 'update']);
    Route::delete('/onboarding/{onboarding_uuid}', [ApiOnboardingController::class, 'destroy']);

    Route::get('/chats/rate-template', [ApiCommunityReputationRulesController::class, 'getTemplate'])->name('chats.reputation.template');
    Route::post('/chats/rate/{uuid}', [ApiCommunityReputationRulesController::class, 'update'])->name('chats.reputation.update');
    Route::post('/chats/rate', [ApiCommunityReputationRulesController::class, 'store'])->name('chats.reputation.store');
    Route::get('/chats/rate', [ApiCommunityReputationRulesController::class, 'list'])->name('chats.reputation.list');
    Route::get('/chats/rate/{uuid}', [ApiCommunityReputationRulesController::class, 'show'])->name('chats.reputation.show');
    Route::delete('/chats/rate/{reputation_rule_uuid}', [ApiCommunityReputationRulesController::class, 'destroy'])->name('chats.reputation.destroy');

    Route::get('/knowledge', [ApiKnowledgeController::class, 'list']);
    Route::post('/knowledge', [ApiKnowledgeController::class, 'store']);
    Route::delete('/knowledge/{id}', [ApiKnowledgeController::class, 'delete']);
    Route::get('/knowledge/{id}', [ApiKnowledgeController::class, 'show']);
    Route::put('/knowledge/{id}', [ApiKnowledgeController::class, 'update']);
    Route::post('/knowledge/bind-communities', [ApiKnowledgeController::class, 'bindToCommunity']);

    Route::post('/chats/rank', [ApiRankRuleController::class, 'store'])->name('chats.rank.store');
    Route::get('/chats/rank', [ApiRankRuleController::class, 'list'])->name('chats.rank.list');
    Route::put('/chats/rank/{uuid}', [ApiRankRuleController::class, 'update'])->name('chats.rank.update');
    Route::get('/chats/rank/{uuid}', [ApiRankRuleController::class, 'show'])->name('chats.rank.show');

    Route::get('/question/list/{id}', [ApiQuestionController::class, 'list']);
    Route::post('/question', [ApiQuestionController::class, 'store']);
    Route::get('/question/{id}', [ApiQuestionController::class, 'show']);
    Route::post('/question/{id}', [ApiQuestionController::class, 'update']);
    Route::delete('/question/{id}', [ApiQuestionController::class, 'delete']);

    Route::get('/question-category', [ApiQuestionCategoryController::class, 'list']);
    Route::post('/question-category', [ApiQuestionCategoryController::class, 'store']);
    Route::get('/question-category/{id}', [ApiQuestionCategoryController::class, 'show']);
    Route::put('/question-category/{id}', [ApiQuestionCategoryController::class, 'update']);
    Route::delete('/question-category/{id}', [ApiQuestionCategoryController::class, 'delete']);

    Route::get('/statistic/members', [ApiTelegramUsersStatistic::class, 'members'])->name('api.statistic.members');
    Route::get('/statistic/members/export', [ApiTelegramUsersStatistic::class, 'exportMembers'])->name('api.statistic.members.export');

    Route::get('/statistic/messages/users', [ApiTelegramMessageStatistic::class, 'messages'])->name('api.statistic.messages');
    Route::get('/statistic/messages/charts', [ApiTelegramMessageStatistic::class, 'messageCharts'])->name('api.statistic.messages.charts');
    Route::get('/statistic/messages/export', [ApiTelegramMessageStatistic::class, 'exportMessages'])->name('api.statistic.messages.export');

    Route::get('/statistic/semantic/export', [ApiSemanticController::class, 'exportSemantic']);
    Route::get('/statistic/semantic/charts', [ApiSemanticController::class, 'charts']);

    Route::get('/statistic/moderation/users', [ApiTelegramModerationStatistic::class, 'userList'])->name('api.statistic.moderation.user_list');
    Route::get('/statistic/moderation/export', [ApiTelegramModerationStatistic::class, 'exportModeration'])->name('api.statistic.moderation.export');

    Route::get('/statistic/export-all-data', [ApiExportAllData::class, 'exportAllData'])->name('api.statistic.export.all_data');

    Route::get('/chats/users/reputation', [TelegramUserReputationController::class, 'index']);

    Route::post('/file', [ApiFileController::class, 'upload']);
    Route::delete('/file/{id}', [ApiFileController::class, 'delete']);

    Route::get('/donates', [ApiNewDonateController::class, 'list']);
    Route::get('/donate/{id}', [ApiNewDonateController::class, 'show']);
    Route::post('/donate', [ApiNewDonateController::class, 'store']);
    Route::delete('/donate/{id}', [ApiNewDonateController::class, 'delete']);
    Route::put('/donate/{id}', [ApiNewDonateController::class, 'update']);
    Route::post('/pay/donate', [ApiNewDonateController::class, 'processDonatePayment']);

    Route::post('/authors', [ApiAuthorController::class, 'store'])->name('api.author.create');
    Route::put('/authors', [ApiAuthorController::class, 'update'])->name('api.author.update');
    Route::get('/authors/{id}', [ApiAuthorController::class, 'show'])->name('api.author.show');
    Route::delete('/authors', [ApiAuthorController::class, 'destroy'])->name('api.author.delete');

    Route::get('/publications', [ApiPublicationController::class, 'list'])->name('api.publications.list');
    Route::post('/publications', [ApiPublicationController::class, 'store'])->name('api.publications.create');
    Route::delete('/publications/{id}', [ApiPublicationController::class, 'destroy'])->name('api.publications.delete');
    Route::post('/publications/{id}', [ApiPublicationController::class, 'update'])->name('api.publications.update');
    Route::get('/publications/{id}', [ApiPublicationController::class, 'show'])->name('api.publications.show');


    Route::post('/publication-parts', [ApiPublicationPartController::class, 'store'])->name('api.publication_parts.create');
    Route::delete('/publication-parts/{id}', [ApiPublicationPartController::class, 'destroy'])->name('api.publication_parts.delete');


    Route::post('/favourite/publications', [ApiFavouritePublicationController::class, 'store'])->name('api.publications.favorite.create');
    Route::delete('/favourite/publications/{id}', [ApiFavouritePublicationController::class, 'destroy'])->name('api.publications.favorite.delete');
    Route::get('/favourite/publications', [ApiFavouritePublicationController::class, 'list'])->name('api.publications.favorite.list');

});

Route::prefix('api/v3/manager')->middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::get('/users', [ApiManagerUserController::class, 'list'])->name('api.manager.users.list');
    Route::get('/users/{id}', [ApiManagerUserController::class, 'show'])->name('api.manager.users.info');
    Route::put('/users/{id}', [ApiManagerUserController::class, 'editCommission'])->name('api.manager.users.edit_commission');
    Route::get('/users/block/{id}', [ApiManagerUserController::class, 'block'])->name('api.manager.users.block');
    Route::get('/users/unblock/{id}', [ApiManagerUserController::class, 'unBlock'])->name('api.manager.users.unblock');
    Route::get('/users/make-admin/{id}', [ApiManagerUserController::class, 'makeUserAdmin'])->name('api.manager.users.make_admin');
    Route::get('/users/revoke-admin/{id}', [ApiManagerUserController::class, 'removeUserFromAdmin'])->name('api.manager.users.revoke_admin');
    Route::get('/users/send-new-password/{id}', [ApiManagerUserController::class, 'sendNewPassword'])->name('api.manager.users.send_new_password');
    Route::get('/export/users', [ApiManagerUserController::class, 'export'])->name('api.manager.users.export');

    Route::post('/feed-back/answer', [ApiAdminFeedBackController::class, 'answer'])->name('api.manager.feed_back.answer');
    Route::get('/feed-back/close/{id}', [ApiAdminFeedBackController::class, 'close'])->name('api.manager.feed_back.close');
    Route::get('/feed-back/show/{id}', [ApiAdminFeedBackController::class, 'show'])->name('api.manager.feed_back.show');
    Route::get('/feed-backs', [ApiAdminFeedBackController::class, 'list'])->name('api.manager.feed_back.list');

    Route::get('/communities', [ApiAdminCommunityController::class, 'list'])->name('api.manager.communities.list');
    Route::get('/communities/{id}', [ApiAdminCommunityController::class, 'show'])->name('api.manager.communities.show');
    Route::get('/export/communities', [ApiAdminCommunityController::class, 'export'])->name('api.manager.communities.export');

    Route::get('/payments', [ApiAdminPaymentController::class, 'list'])->name('api.manager.payments.list');
    Route::get('/payments/customers', [ApiAdminPaymentController::class, 'customers'])->name('api.manager.payments.customers');
    Route::get('/export/payments', [ApiAdminPaymentController::class, 'export'])->name('api.manager.payments.export');

});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers')->group(function () {
    Route::post('/session/put', 'Controller@sessionPut')->name('api.session.put');
    Route::get('/session/get', 'Controller@sessionGet')->name('api.session.get');
    Route::post('/session/flush', 'Controller@sessionFlush')->name('api.session.flush');
});

Route::middleware('auth:sanctum')->namespace('App\Http\Controllers')->group(function () {
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
        Route::post('list', 'ApiQuestionController@list')->name('api.question.list');
        Route::post('get', 'ApiQuestionController@get')->name('api.question.get');
        Route::post('add', 'ApiQuestionController@add')->name('api.question.add');
        Route::post('store', 'ApiQuestionController@store')->name('api.question.store');
        Route::post('delete', 'ApiQuestionController@delete')->name('api.question.delete');
        Route::post('do', 'ApiQuestionController@do')->name('api.question.do');
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
