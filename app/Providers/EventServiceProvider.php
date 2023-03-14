<?php

namespace App\Providers;

use App\Events\ApiUserRegister;
use App\Events\BuyCourse;
use App\Events\BuyCourseListener;
use App\Events\CreateCommunity;
use App\Events\FeedBackAnswer;
use App\Events\FeedBackCreate;
use App\Events\RemindPassword;
use App\Events\SubscriptionMade;
use App\Listeners\AssignStartSubscription;
use App\Listeners\CreateCommunityListener;
use App\Listeners\FeedBackAnswerListener;
use App\Listeners\FeedBackListener;
use App\Listeners\RemindPasswordListener;
use App\Listeners\SubscriptionListener;
use App\Listeners\UserRegisterSendEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ApiUserRegister::class=>[
            AssignStartSubscription::class,
            UserRegisterSendEmail::class
        ],
        CreateCommunity::class=>[
            CreateCommunityListener::class
        ],
        FeedBackCreate::class=>[
            FeedBackListener::class
        ],
        SubscriptionMade::class=>[
            SubscriptionListener::class
        ],
        RemindPassword::class=>[
            RemindPasswordListener::class
        ],
        FeedBackAnswer::class=>[
            FeedBackAnswerListener::class
        ],
        BuyCourse::class=>[
            BuyCourseListener::class
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
