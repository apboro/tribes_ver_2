<?php

namespace Tests\Unit\filters;

use App\Filters\API\QueryAPIFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueryAPIFilterTest extends TestCase
{
    /** @var QueryAPIFilter */
    protected $filter;
    protected $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filter = app()->make(TestFilter::class);
        $this->builder = DB::table('users');

    }

    public function testSortingByDefault()
    {
        $builder = $this->filter->apply($this->builder);
        $this->assertEquals($builder->toSql(), 'select * from "users" order by "created_at" desc');
    }

    public function testSortingByName()
    {
        $this->filter->replace([
            'sort' => [
                'name' => 'name',
                'rule' => 'asc',
            ],
        ]);
        $builder = $this->filter->apply($this->builder);
        $this->assertEquals($builder->toSql(), 'select * from "users" order by "title" asc');
    }

    public function testSortingByDate()
    {
        $this->filter->replace([
            'sort' => [
                'name' => 'date',
                'rule' => 'asc',
            ],
        ]);
        $builder = $this->filter->apply($this->builder);
        $this->assertEquals($builder->toSql(), 'select * from "users" order by "created_at" asc');
    }

    public function testFilterByAttribute()
    {
        $this->filter->replace([
            'id' => 1,
        ]);
        $builder = $this->filter->apply($this->builder);
        $this->assertEquals($builder->toSql(), 'select * from "users" where "id" = ? order by "created_at" desc');
    }

    public function testFilterCaptureAttributesFromRequest()
    {
        /** @var Request $request */
        $request = app()->make(Request::class);
        $request->replace(['filter' => [
            'id' => 2,
            'sort' => [
                'name' => 'name',
                'rule' => 'asc',
            ],
        ]]);
        $filter = app()->make(TestFilter::class, ['request' => $request]);
        $builder = $filter->apply($this->builder);
        $this->assertEquals($builder->toSql(), 'select * from "users" where "id" = ? order by "title" asc');

        $bindings = $builder->getBindings();
        $this->assertCount(1,$bindings);
        $this->assertEquals(2,reset($bindings));
    }

    public function testFilterGetRequest()
    {
        $this->assertInstanceOf(Request::class, $this->filter->getRequest());
    }
}

class TestFilter extends QueryAPIFilter
{

    protected function _sortingName($name): string
    {
        $list = [
            'name' => 'title',
            'date' => 'created_at',
        ];
        return $list[$name] ?? $list['date'];
    }

    public function id($value)
    {
        return $this->builder->where('id', $value);
    }
}
