<?php


namespace App\Filters;

use Illuminate\Support\Carbon;

class PaymentFilter extends QueryFilter
{
    public function id($id)
    {
        return $this->builder->where('OrderId', 'like', '%' . $id . '%');
    }

    public function from($str)
    {
        return $this->builder->where('from', 'like', '%' . $str . '%');
    }

    public function community($id)
    {
        return $this->builder->where('community_id', $id);
    }

    public function date($date)
    {
        return $this->builder->whereDate('created_at',$date);
    }

    public function type($id)
    {
        return $id ? $this->builder->where('type', $id) : $this->builder;
    }

    public function search($string)
    {
//        dd($string);
        return $this->builder
            ->orWhere('from', 'like', '%' . $string . '%')
            ->orWhere('add_balance', 'like', '%' . $string . '%')
            ->orWhereHas('community', function($q) use ($string){
                $q->where('title', 'like', '%' . $string . '%');
            });
    }
}