<?php

namespace App\Providers;

use App\Helper\Data;
use App\Services\Telegram\MainComponents\KnowledgeObserver;
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
