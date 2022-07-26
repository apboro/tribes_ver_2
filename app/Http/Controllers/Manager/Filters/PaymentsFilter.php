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

    public function sort(array $data)
    {
        $name = $this->sortingColumn($data['name']);
        $rule = $this->sortingRule($data['rule']);
        $this->builder->orderBy($name, $rule);
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

    private function sortingColumn($column)
    {
        $list = [
            'user' => 'user_id',
            'date' => 'created_at',
            'default' => 'id'
        ];

        return $list[$column] ?? $list['default'];
    }

    private function sortingRule($rule)
    {
        $list = [
            'asc' => 'asc',
            'desc' => 'desc',
            'default' => 'desc'
        ];

        return $list[$rule] ?? $list['default'];
    }
}