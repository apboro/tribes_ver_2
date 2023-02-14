<?php


namespace App\Http\Controllers\Manager\Filters;


use App\Filters\API\QueryAPIFilter;

class FeedbackFilter extends QueryAPIFilter
{
    public function search($string)
    {
        $string = strtolower($string);
        return $this->builder
            ->where('name', 'ilike', "%{$string}%")
            ->orWhere('id', 'like', "%{$string}%")
            ->orWhere('email', 'ilike', "%{$string}%");
    }

    public function entries($string)
    {
        return $this->builder->limit($string);
    }

    public function status($status)
    {
        if ($status === 'Все статусы') {
            return $this->builder;
        } else {
            return $this->builder->where('status', $status);
        }
    }

    protected function _sortingName($name): string
    {
        $list = [
            'title' => 'title',
            'followers' => 'owner',
            'balance' => 'balance',
            'created_at' => 'created_at',
        ];
        return $list[$name] ?? $list['created_at'];
    }

}