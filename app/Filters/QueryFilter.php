<?php

namespace App\Filters;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected $request;

    protected $builder;

    protected $splitter = ',';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function filters(): array
    {
        $filters = $this->request->wantsJson() ? $this->request->json()->all() : $this->request->query();

        if (!is_array($filters)) {
            throw new Exception('Request должен быть array');
        }

        return count($filters) ? $filters : $this->request->all();
    }

    /**
     * @param Builder|QueryBuilder $builder
     * @return Builder|QueryBuilder
     * @throws Exception
     */
    public function apply( $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            $name = Str::camel($name);
            if (method_exists($this, $name) && $value !== null) {
                call_user_func_array([$this, $name], array_filter([$value], function ($k) {
                    return true;
                }, ARRAY_FILTER_USE_BOTH));
            }
        }

        return $this->builder;
    }

    protected function paramToArray($param)
    {
        return explode($this->splitter, $param);
    }
}