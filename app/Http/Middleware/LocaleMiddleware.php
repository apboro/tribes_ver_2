<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Auth;

class LocaleMiddleware
{
    public static $mainLanguage = 'ru';

    public static $languages = ['en', 'ru'];

    public function handle(Request $request, Closure $next)
    {

//        $resp = $next($request);
//        if(Auth::check()){
//            $locale = Auth::user()->locale;
//        } else {
            $locale = self::getLocale();
//        }

        if($locale) App::setLocale($locale);

        else App::setLocale(self::$mainLanguage);

        return $next($request);
    }

    public static function getLocale()
    {
        $uri = request()->path();

        $segmentsURI = explode('/',$uri);

        if (!empty($segmentsURI[0]) && in_array($segmentsURI[0], self::$languages)) {
            if ($segmentsURI[0] != self::$mainLanguage) return $segmentsURI[0];
        }

        return null;
    }
}
