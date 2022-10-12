<?php

use App\Http\Controllers\API\TeleDialogStatisticController;
use App\Http\Controllers\API\TeleMessageStatisticController;
use App\Http\Controllers\DonateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestBotController;
use App\Http\Controllers\TelegramBotController;
use App\Http\Controllers\TelegramUserBotController;
use App\Http\Controllers\UserBotFormController;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
})->name('main');

//Route::get('/', function () {
//    return view('auth.login');
//})->name('main');

Route::post('/test/{state}', function () {

    $testing = \Illuminate\Support\Facades\Session::get('testing');
    \Illuminate\Support\Facades\Session::put('testing', !$testing);
    return redirect()->back();
});

Auth::routes();

Route::group(['prefix' => App\Http\Middleware\LocaleMiddleware::getLocale()], function () {

    Route::namespace('App\Http\Controllers')->group(function () {
        //Payments
        Route::post('/payment/donate/range', 'DonateController@takeRangeDonatePayment')->name('payment.donate.range');
        Route::get('/payment/donate/{hash}', 'DonateController@donatePage')->name('community.donate.form');
        Route::get('/payment/{hash}/success/{telegramId?}', 'PaymentController@successPage')->name('payment.success');

        Route::get('/payments', 'PaymentController@list')->name('payment.list');
        Route::get('/payments/card', 'PaymentController@cardList')->name('payment.card.list');
        Route::get('/payments/card/add', 'PaymentController@cardAdd')->name('payment.card.add');
        Route::get('/payments/income', 'PaymentController@incomeList')->name('payment.income.list');
        Route::get('/payments/outcome', 'PaymentController@outcomeList')->name('payment.outcome.list');
    });


    //TINKOFF API
    Route::namespace('App\Http\Controllers')->group(function () {
        Route::any('/tinkoff/notify', 'PaymentController@notify')->name('tinkoff.notify');
    });

    // Публичные ссылки на вопросы
    Route::namespace('App\Http\Controllers')->group(function () {
        Route::get('/{hash}/questions', 'KnowledgeController@list')->name('public.knowledge.list');
        Route::get('/{hash}/questions/{question}/view', 'KnowledgeController@get')->name('public.knowledge.view');
    });


    // Авторизованные роуты
    Route::middleware('auth')->namespace('App\Http\Controllers')->group(function () {

        //New Design Routes
        Route::get('/analytics/subscribers/{project?}/{community?}', 'ProjectController@subscribers')->name('project.analytics.subscribers');
        Route::get('/analytics/messages/{project?}/{community?}', 'ProjectController@messages')->name('project.analytics.messages');
        Route::get('/analytics/payments/{project?}/{community?}', 'ProjectController@payments')->name('project.analytics.payments');
        Route::get('/analytics/{project?}/{community?}', 'ProjectController@analytics')->name('project.analytics');

        Route::get('/donates/{project?}/{community?}', 'ProjectController@donates')->name('project.donates');
        Route::get('/tariffs/{project?}/{community?}', 'ProjectController@tariffs')->name('project.tariffs');
        Route::get('/members/{project?}/{community?}', 'ProjectController@members')->name('project.members');
        Route::get('profile/projects', 'ProjectController@listProjects')->name('profile.project.list');
        Route::get('profile/communities', 'ProjectController@listCommunities')->name('profile.communities.list');
        Route::any('profile/projects/add', 'ProjectController@add')->name('profile.project.add');
        Route::any('profile/projects/edit/{project}', 'ProjectController@edit')->name('profile.project.edit');

        Route::group(['prefix' => 'profile'], function () {

            Route::get('/', 'AuthorController@profile')->name('author.profile');
            Route::get('/messengers', 'AuthorController@messengerList')->name('author.messenger.list');
            Route::get('/mobile', 'AuthorController@mobileConfirmed')->name('author.mobile.form');
            Route::get('/reset/mobile', 'AuthorController@resetConfirmed')->name('author.mobile.reset');
            Route::get('/password', 'AuthorController@passwordForm')->name('author.password.form');

            Route::post('/password/change', 'ProfileController@passChange')->name('profile.password.change');
            Route::post('/confirmed/mobile', 'AuthorController@confirmed')->name('author.mobile.confirmed');
            Route::post('/confirmed/code', 'AuthorController@confirmedCode')->name('author.mobile.code');
            Route::post('/assign/telegram', 'AuthorController@assignTelegramAccount')->name('author.profile.assign.telegram');
            Route::post('/detach/telegram', 'AuthorController@detachTelegramAccount')->name('author.profile.detach.telegram');
        });

        //Follower
        Route::group(['prefix' => 'follower'], function () {
            Route::get('/', 'FollowerController@mediaProducts')->name('follower.products');

            Route::get('/product/{hash}', 'FollowerController@product')->name('follower.product');

            Route::get('/faq', function () {
                return view('common.faq.index');
            })->name('follower.faq.index');
            Route::get('/education', function () {
                return view('common.education.index');
            })->name('follower.education.index');

            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', 'AuthorController@profile')->name('follower.profile');
                Route::get('/messengers', 'AuthorController@messengerList')->name('follower.messenger.list');
                Route::get('/mobile', 'AuthorController@mobileConfirmed')->name('follower.mobile.form');
                Route::get('/reset/mobile', 'AuthorController@resetConfirmed')->name('follower.mobile.reset');
                Route::get('/password', 'AuthorController@passwordForm')->name('follower.password.form');

                Route::post('/confirmed/mobile', 'AuthorController@confirmed')->name('follower.mobile.confirmed');
                Route::post('/confirmed/code', 'AuthorController@confirmedCode')->name('follower.mobile.code');
                Route::post('/assign/telegram', 'AuthorController@assignTelegramAccount')->name('follower.profile.assign.telegram');
                Route::post('/detach/telegram', 'AuthorController@detachTelegramAccount')->name('follower.profile.detach.telegram');
            });


        });


        // Content
        Route::any('/video/add', 'PostController@saveVideo')->name('save.video');
        // Community


        Route::group(['prefix' => 'community'], function () {

            Route::get('/', 'CommunityController@index')->name('community.list');

            Route::middleware('sms_confirmed', 'owned_group_community')->group(function () {
                // Statistic
                Route::get('/{community}/statistic', 'CommunityController@statistic')->name('community.statistic');
                Route::get('/{community}/statistic/subscriber', 'CommunityController@statisticSubscribers')->name('community.statistic.subscribers');
                Route::get('/{community}/statistic/messages', 'CommunityController@statisticMessages')->name('community.statistic.messages');
                Route::get('/{community}/statistic/payments', 'CommunityController@statisticPayments')->name('community.statistic.payment');

            });

            Route::middleware('sms_confirmed', 'owned_community')->group(function () {

                Route::get('{community}', 'CommunityController@statistic')->where(['community' => '[0-9]+'])->name('community.view');



                // Donate
                Route::get('/{community}/donate/list', 'DonateController@list')->name('community.donate.list');
                Route::get('/{community}/donate/add/{id?}', 'DonateController@add')->name('community.donate.add');
                Route::get('/{community}/donate/remove/{id}', 'DonateController@remove')->name('community.donate.remove');
                // Route::get('/{community}/donate/{id?}', 'DonateController@edit')->name('community.donate');
                Route::get('/{community}/donate/settings/{id?}', 'DonateController@donateSettings')->name('community.donate.settings');
                Route::post('/{community}/donate/update/{id?}', 'DonateController@donateUpdate')->name('community.donate.update');
                Route::post('/{community}/donate/settings/update/{id?}', 'DonateController@donateSettingsUpdate')->name('community.donate.settings.update');

                // Tariff

                Route::any('/{community}/tariff/add', 'TariffController@tariffAdd')->name('community.tariff.add');
                Route::any('/{community}/tariff/edit/{id}/{activate?}', 'TariffController@tariffEdit')->name('community.tariff.edit');

                Route::get('/{community}/subscribers', 'TariffController@subscriptions')->name('community.tariff.subscriptions');
                Route::post('/{community}/subscribers/change', 'TariffController@subscriptionsChange')->name('community.tariff.subscriptionsChange');

                Route::get('/{community}/tariff', 'TariffController@list')->name('community.tariff.list');

                Route::get('/{community}/tariff/settings/{tab?}', 'TariffController@settings')->name('community.tariff.settings');
                Route::get('/{community}/tariff/publication/{tab?}', 'TariffController@publication')->name('community.tariff.publication');

                Route::post('/{community}/tariff/settings/update', 'TariffController@tariffSettings')->name('tariff.settings.update');


                // Knowledge
                Route::get('/{community}/knowledge', function () {
                    return view('common.knowledge.index');
                })->name('knowledge.index');

                Route::get('/{community}/knowledge/list', function () {
                    return view('common.knowledge.list2');
                })->name('common.knowledge.list');

            });

        });


        Route::get('/{hash}/knowledge/help', 'KnowledgeController@help')->name('public.knowledge.help');

        Route::get('/community/add', function () {
            return view('common.community.form');
        })->name('community.add');

        Route::post('/community/invoke', 'CommunityController@initCommunityConnect')->name('invoke.community.connect');
        Route::post('/community/connection/check', 'CommunityController@checkCommunityConnect')->name('check.community.connect');

        // Audience
        Route::get('/audience', 'AuthorController@audience')->name('audience.list');
        Route::get('/audience/ban', 'AuthorController@audienceBan')->name('audience.ban');
        Route::get('/audience/delete', 'AuthorController@audienceDelete')->name('audience.delete');

        // Donate
        Route::get('/donate/success', function () {
            return view('common.donate.success');
        })->name('donate.success');

        // Tariff
        Route::get('/tariff', function () {
            return view('common.tariff.index');
        })->name('tariff.index');

        // FAQ
        Route::get('/faq', function () {
            return view('common.faq.index');
        })->name('faq.index');

        // Education
        Route::get('/education', function () {
            return view('common.education.index');
        })->name('education.index');

        // courses
        Route::get('/courses', 'CourseController@list')->name('course.list');

        // course edit
//        Route::get('/courses/edit', function () {
//            return view('common.course.edit');
//        })->name('course.edit');

        Route::get('/courses/edit', 'CourseController@courseEditor')->name('course.edit');
        Route::get('/courses/new', 'CourseController@newCourse')->name('course.new');

        Route::post('/courses/{id}/feedback', 'CourseController@feedback')->name('course.feedback');

//        Route::get('/courses/new', function () {
//            return view('common.course.edit');
//        })->name('course.new');

        // course common
//        Route::get('/courses/edit/common', function () {
//            return view('common.course.common');
//        })->name('course.edit.common');

        // course public
        Route::get('/courses/edit/public', function () {
            return view('common.course.public');
        })->name('course.edit.public');

        // course education materials
        Route::get('/courses/edit/education_materials', function () {
            return view('common.course.education_materials');
        })->name('course.edit.education_materials');

        // course tariffs
        Route::get('/courses/edit/tariffs', function () {
            return view('common.course.tariffs');
        })->name('course.edit.tariffs');

        // course settings
        Route::get('/courses/edit/settings', function () {
            return view('common.course.settings');
        })->name('course.edit.settings');

        // lesson
        Route::get('/courses/esit/lesson/common', function () {
            return view('common.lesson.common');
        })->name('lesson.edit.common');

        Route::get('/courses/{hash}/view', 'CourseController@view')->name('course.view');
    });
});

Route::any('media/{hash}', 'App\Http\Controllers\CourseController@mediaFormPay')->name('course.payment');
Route::post('media/{hash}/pay', 'App\Http\Controllers\CourseController@pay')->name('course.pay');
Route::get('media/{hash}/success', 'App\Http\Controllers\CourseController@success')->name('course.pay.success');

Route::any('community/{community}/tariff/form', 'App\Http\Controllers\TariffController@tariffFormPay')->name('community.tariff.form');
Route::any('community/{hash}', 'App\Http\Controllers\TariffController@tariffPayment')->name('community.tariff.payment');

// Footer Routes
Route::get('/privacy', function () {
    return view('common.privacy.index');
})->name('privacy.index');

Route::get('/payment_processing', function () {
    return view('common.payment_processing.index');
})->name('payment_processing.index');

Route::get('/sub_terms', function () {
    return view('common.sub_terms.index');
})->name('sub_terms.index');

Route::get('/terms', function () {
    return view('common.terms.index');
})->name('terms.index');

Route::get('/privacy_accept', function () {
    return view('common.privacy_accept.index');
})->name('privacy_accept.index');

Route::get('/ad_accept', function () {
    return view('common.ad_accept.index');
})->name('ad_accept.index');

Route::get('/agency_contract', function () {
    return view('common.agency_contract.index');
})->name('agency_contract.index');

Route::get('/confirm_subscription', function () {
    return view('common.tariff.confirm-subscription');
})->name('сonfirm_subscription');

Route::get('setlocale/{lang}', function ($lang) {

    $referer = redirect()->back()->getTargetUrl();
    $parse_url = parse_url($referer, PHP_URL_PATH);
    $segments = explode('/', $parse_url);

    if (Auth::check()) Auth::user()->setLocale($lang);

    if (in_array($segments[1], App\Http\Middleware\LocaleMiddleware::$languages)) {

        unset($segments[1]);
    }
    if ($lang != App\Http\Middleware\LocaleMiddleware::$mainLanguage) {
        array_splice($segments, 1, 0, $lang);
    }
    $url = request()->root() . implode("/", $segments);
    if (parse_url($referer, PHP_URL_QUERY)) {
        $url = $url . '?' . parse_url($referer, PHP_URL_QUERY);
    }

    return $url;
})->name('setlocale');

Route::group(['prefix' => 'bot'], function () {
    Route::match(['get', 'post'], 'webhook', [TelegramBotController::class, 'index']);
    Route::match(['get', 'post'], 'webhook-bot2', [TelegramBotController::class, 'index-bot2']);
});

Route::middleware(['auth'])->group(function() {
    Route::get('/user-bot-form', [UserBotFormController::class, 'index'])->name('user.bot.form');
});

Route::any('/webhook-user-bot', [TelegramUserBotController::class, 'index'])->name('user.bot.webhook');
Route::get('/set-webhook-for-user-bot', [TelegramUserBotController::class, 'setWebhook']);

Route::any('/test', [TestBotController::class, 'index']);

Route::any('/manager{any}', function () {
    return view('admin');
})->where('any', '.*')->name('web.manager');

Route::any('/telegram', 'App\Http\Controllers\InterfaceComtroller@index')->name('telegram.interface');

Route::get('/tinkofftestdata', 'App\Http\Controllers\TariffController@testData');

