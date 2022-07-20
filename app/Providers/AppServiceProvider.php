<?php

namespace App\Providers;

use App\Helper\Data;
use App\Services\Telegram\MainComponents\KnowledgeObserver;
use App\Services\Tinkoff\TinkoffApi;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use PseudoCrypt;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('pseudoCrypt',function(){
            return new PseudoCrypt();
        });
        $this->app->bind('data',function(){
            return new Data();
        });
        $this->app->bind('knowledgeObserver',function(){
            return app()->make(KnowledgeObserver::class);
        });

        $this->app->bind('payTerminal',function(){
            return app()->make(TinkoffApi::class,[
                'terminalKey' => env('TINKOFF_TERMINAL_KEY'),
                'secretKey' => env('TINKOFF_SECRET_KEY'),
            ]);
        });

        $this->app->bind('e2cTerminal',function(){
            return app()->make(TinkoffApi::class,[
                'terminalKey' => env('TINKOFF_TERMINAL_KEY_E2C'),
                'secretKey' => env('TINKOFF_SECRET_KEY_E2C'),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->setlocale(request()->get('lang'));


        Blade::directive('lang', function ($q) {
            return __($q);
        });

        JsonResource::withoutWrapping();
    }
}
