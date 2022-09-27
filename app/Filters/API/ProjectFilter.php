<?php

namespace App\Filters\API;

class ProjectFilter extends QueryAPIFilter
{

    protected function _sortingName($name): string
    {
        $list = [
            'title' => 'title',
            'created_at' => 'created_at',
        ];
        return $list[$name] ?? $list['created_at'];
    }

    public function userId(int $value)
    {
        return $this->builder->where('user_id','=',$value);
    }

    public function projectId(int $value)
    {
        return $this->builder->where('id','=',$value);
    }
}