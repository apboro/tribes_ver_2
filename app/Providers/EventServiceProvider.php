<?php

namespace App\Providers;

use App\Events\ApiUserRegister;
use App\Events\CreateCommunity;
use App\Events\FeedBackCreate;
use App\Events\SubscriptionMade;
use App\Listeners\AssignStartSubscription;
use App\Listeners\CreateCommunityListener;
use App\Listeners\FeedBackListener;
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
