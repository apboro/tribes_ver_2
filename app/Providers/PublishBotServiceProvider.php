<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Askoldex\Teletant\Bot;
use Askoldex\Teletant\Settings;

class PublishBotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->singleton(Settings::class, function(){
        //     $settings = new Settings(config('telegram_bot.bot.token'));
        //     $settings->setHookOnFirstRequest((bool) config('telegram_bot.bot.hook_on_first_request'));
        //     return $settings;
        // });
        // $this->app->singleton(Bot::class, function(){
        //     $bot = new Bot(app(Settings::class));
        //     return $bot;
        // });
    }
}
