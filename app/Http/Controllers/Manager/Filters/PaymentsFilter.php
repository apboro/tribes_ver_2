<?php


namespace App\Http\Controllers\Manager\Filters;


use App\Filters\API\QueryAPIFilter;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentsFilter extends QueryAPIFilter
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

    public function date($date)
    {
        $date = Carbon::parse($date);

        return $this->builder
            ->whereDate('created_at', $date);
    }

    public function from($id)
    {
        return $this->builder->where('user_id', $id)    ;
    }

    protected function _sortingName($name): string
    {
        $list = [
            'user' => 'user_id',
            'date' => 'created_at',
            'default' => 'id'
        ];
        return $list[$name] ?? $list['date'];
    }
}