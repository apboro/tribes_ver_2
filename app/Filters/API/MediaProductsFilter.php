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

    public function sort(array $data)
    {
        $name = ArrayHelper::getValue($data, 'name', 'id');
        $name = $this->_sortingName(strtolower($name));
        $rule = ArrayHelper::getValue($data, 'rule', 'desc');
        $rule = $this->_sortingRule(strtolower($rule));
        $this->builder->orderBy($name,$rule);
    }

    public function owner($value)
    {
        return $this->builder->where(['courses.owner' => $value]);
    }

}