<?php


namespace App\Http\Controllers\Manager\Filters;


use App\Filters\API\QueryAPIFilter;
use Illuminate\Support\Facades\DB;

class CommunityFilter extends QueryAPIFilter
{
    public function search($string)
    {
        $string = strtolower($string);
        return $this->builder
            ->where('title', 'ilike', "%{$string}%")
            ->orWhere('id','like', "%{$string}%")
            ->orWhereHas('communityOwner', function($q) use ($string){
                $q->where('name', 'ilike', "%{$string}%");
            });

    }

    public function entries($string)
    {
        return $this->builder->limit($string);
    }

    protected function _sortingName($name): string
    {
        $list = [
            'title' => 'title',
            'followers' => 'followers_count',
            'balance' =>'balance',
            'created_at' => 'created_at',
        ];
        return $list[$name] ?? $list['created_at'];
    }

}