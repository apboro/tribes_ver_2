<?php

namespace App\Providers;

use Illuminate\Log\Logger;
use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\Log;
use App\Services\Telegram\MainBotCollection;
use App\Services\Telegram\MainBot;
use Illuminate\Support\ServiceProvider;
use Askoldex\Teletant\Bot;
use Askoldex\Teletant\Settings;

class TelegramBotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\TelegramConnection\TelegramConnectionRepositoryContract::class,
            \App\Repositories\TelegramConnection\TelegramConnectionRepository::class
        );

        $this->app->bind(
            \App\Services\Telegram\BotInterface\BotContract::class,
            \App\Services\Telegram\MainBot::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(MainBotCollection::class, function(){
            $botCollect = new MainBotCollection();
            foreach(config('telegram_bot') as $key => $botSettings){
                $botCollect->add($botSettings);
            }

            return $botCollect;
        });
    }
}

