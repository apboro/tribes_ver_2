<?php

namespace App\Providers;

use App\Helper\Data;
use App\Services\File\common\FileConfig;
use App\Services\Telegram\MainComponents\KnowledgeObserver;
use Illuminate\Config\Repository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
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

        $this->app->when(FileConfig::class)
            ->needs(Repository::class)
            ->give(function () {
                return Config::get('file_upload');
            });

        /*$this->app->bind(FileConfig::class, function(){
//            return app()->make(Repository::class, ['items' => Storage::disk('config')->get('file_upload.php')]);

//            dd(Storage::disk('local'));
//            dd(Storage::disk('config')->path('file_upload.php'));
//            dd( Config::get('file_upload') );
            $config = Config::get('file_upload');
//            dd($config);

            return app()->make(Repository::class, ['items' => 'test']);
        });*/
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
