<?php


namespace App\Filters;


use App\Filters\API\QueryAPIFilter;
use Illuminate\Support\Carbon;

class TariffFilter extends QueryAPIFilter
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

    public function member($value)
    {
        if(in_array($value,['active', 'not_active'])){
            if($value == 'active'){
                $this->builder->whereNull('telegram_users_community.exit_date');
            } else {
                $this->builder->whereNotNull('telegram_users_community.exit_date');
            }
        }
        return $this->builder;
    }

    protected function _sortingName($name): string
    {
        $list = [
            'user_name' => 'user_name',
            'role' => 'role',
            'first_name' => 'first_name',
            'accession_date' => 'accession_date',
        ];
        return $list[$name] ?? $list['accession_date'];
    }
}