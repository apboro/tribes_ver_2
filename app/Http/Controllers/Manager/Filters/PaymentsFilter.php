<?php


namespace App\Http\Controllers\Manager\Filters;


use App\Filters\QueryFilter;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentsFilter extends QueryFilter
{
    public function search($string)
    {
        $string = Str::lower($string);

        return $this->builder->where(function($query) use ($string){
           $query
               ->where('from', 'ilike', "%{$string}%")
               ->orWhere('OrderId', 'ilike', "%{$string}%");
        });
    }

    public function sortingUser($sort)
    {
        return $this->builder
            ->orderBy('user_id',$sort);
    }

    public function sortingDate($sort)
    {
        return $this->builder
            ->orderBy('created_at',$sort);
    }

    public function date($date)
    {
        $date = Carbon::parse($date);

        return $this->builder
            ->whereDate('created_at', $date);
    }
}