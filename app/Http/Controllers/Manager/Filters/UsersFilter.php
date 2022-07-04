<?php


namespace App\Http\Controllers\Manager\Filters;


use App\Filters\QueryFilter;
use Illuminate\Support\Facades\DB;

class UsersFilter extends QueryFilter
{
    public function search($string)
    {
        $string = strtolower($string);
        return $this->builder
            ->where(DB::raw('lower(name)'), 'like', '%' . $string . '%')
            ->orWhere('phone', 'like', '%' . $string . '%')
            ->orWhere('id', 'like', '%' . $string . '%');
//            ->orWhereHas('id', function($q) use ($string){
//                $q->where('title', 'like', '%' . $string . '%');
//            });
    }

    public function entries($string)
    {
        return $this->builder->limit($string);
    }
}