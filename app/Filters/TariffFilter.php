<?php


namespace App\Filters;


use Illuminate\Support\Carbon;

class TariffFilter extends QueryFilter
{
    public function search($search)
    {
        return $this->builder->where('first_name', 'like', '%' . $search . '%')
        ->orWhere('last_name', 'like', '%' . $search . '%')
        ->orWhere('user_name', 'like', '%' . $search . '%');
    }

    public function tariff($id)
    {
        return $this->builder->whereHas('tariffVariant', function ($q) use($id) {
            $q->where('id', $id);
        });
    }

    public function from($string)
    {
        return $this->builder->where('user_name', 'like', '%'. $string . '%');
    }

    public function date($date)
    {
        return $this->builder->whereHas('payment', function ($q) use($date) {
            $q->whereDate('created_at', $date);
        });
    }
}