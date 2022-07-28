<?php

namespace App\Filters\API;

use App\Helper\ArrayHelper;
use Illuminate\Http\Request;

class MediaSalesFilter extends QueryAPIFilter
{
    protected function _sortingName($name): string
    {
        $list = [
            'create_date' => 'created_at',
            'update_date' => 'updated_at',
            'price' => 'price',

        ];
        return $list[$name] ?? $list['created_at'];
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

    public function user($value)
    {
        return $this->builder->where(['user_id' => $value]);
    }


}