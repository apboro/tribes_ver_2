<?php


namespace App\Helper;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;

class Data extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'data';
    }

    public function get($key)
    {
        if(Auth::check()){
            $data = \App\Models\Data::where('user_id', Auth::user()->id)->where('key', $key)->first();

            if($data){
                return $data->value;
            }
        }
    }
}