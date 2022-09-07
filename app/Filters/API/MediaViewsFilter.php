<?php

namespace App\Filters\API;

use App\Helper\ArrayHelper;

class MediaViewsFilter extends QueryAPIFilter
{
    protected function _sortingName($name): string
    {
        $list = [
            'create_date' => 'courses.created_at',
            'update_date' => 'courses.updated_at',

        ];
        return $list[$name] ?? $list['create_date'];
    }

    public function owner($value)
    {
        return $this->builder->where(['courses.owner' => $value]);
    }

}