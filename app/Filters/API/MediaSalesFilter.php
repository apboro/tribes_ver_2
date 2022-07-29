<?php

namespace App\Filters\API;

use App\Helper\ArrayHelper;
use Illuminate\Http\Request;

class MediaSalesFilter extends QueryAPIFilter
{
    protected function _sortingName($name): string
    {
        $list = [
            'create_date' => 'm_product_sales.created_at',
            'update_date' => 'm_product_sales.updated_at',
            'price' => 'm_product_sales.price',
            'status' => 'payments.status',
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

    public function buyer($value)
    {
        return $this->builder->where(['m_product_sales.user_id' => $value]);
    }

    public function owner($value)
    {
        return $this->builder->where(['courses.owner' => $value]);
    }

    public function status($value)
    {
        return $this->builder->where(['payments.status' => $value]);
    }
}