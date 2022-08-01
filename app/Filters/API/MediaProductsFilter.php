<?php

namespace App\Filters\API;

use App\Helper\ArrayHelper;

class MediaProductsFilter extends QueryAPIFilter
{
    protected function _sortingName($name): string
    {
        $list = [
            'create_date' => 'courses.created_at',
            'update_date' => 'courses.updated_at',
            'actual_price' => 'courses.price',
            //'status' => 'm_product.c_time_view',
        ];
        return $list[$name] ?? $list['create_date'];
    }

    public function page()
    {
        return $this->builder;
    }

    //зарезервировано под пагинацию
    public function perPage()
    {
        return $this->builder;
    }

    public function owner($value)
    {
        return $this->builder->where(['courses.owner' => $value]);
    }

}