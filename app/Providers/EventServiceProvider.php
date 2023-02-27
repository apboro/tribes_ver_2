<?php

namespace App\Providers;

use App\Events\ApiUserRegister;
use App\Events\CreateCommunity;
use App\Listeners\CreateCommunityListener;
use App\Listeners\UserRegisterSendEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
            UserRegisterSendEmail::class
        ],
        CreateCommunity::class=>[
            CreateCommunityListener::class
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
