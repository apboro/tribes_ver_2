<?php

namespace App\Filters\API;

use App\Filters\QueryFilter;
use App\Helper\ArrayHelper;
use Illuminate\Http\Request;

abstract class QueryAPIFilter extends QueryFilter
{
    const SORT_DESC = 'desc';
    const SORT_ASC = 'asc';
    const SORT_DEFAULT = 'default';

    abstract protected function _sortingName($name): string;

    protected function _sortingRule($rule): string
    {
        $list = [
            self::SORT_DESC => 'desc',
            self::SORT_ASC => 'asc',
            self::SORT_DEFAULT => 'desc',
        ];
        return $list[$rule] ?? $list[self::SORT_DEFAULT];
    }

    public function sort(array $data)
    {
        $name = ArrayHelper::getValue($data, 'name', $this->_sortingName('default'));
        $name = $this->_sortingName(strtolower($name));
        $rule = ArrayHelper::getValue($data, 'rule', self::SORT_DEFAULT);
        $rule = $this->_sortingRule(strtolower($rule));
        $this->builder->orderBy($name,$rule);
    }

    public function filters() : array
    {
        $filters = $this->request->get('filter', []);
        //фильтры сортировки по умолчанию
        $filters['sort'] = $filters['sort']??['name'=>$this->_sortingName('default')];
        return $filters;
    }

    public function replace(array $params): void
    {
        $this->request->replace($params);
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    //зарезервировано под пагинацию
    public function page()
    {
        return $this->builder;
    }

    //зарезервировано под пагинацию
    public function perPage()
    {
        return $this->builder;
    }
}