<?php

namespace Tests\Unit\filters;

use App\Filters\API\QueryAPIFilter;
use App\Models\User;
use App\Services\Knowledge\ManageQuestionService;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\TestCase;

class QueryAPIFilterTest extends TestCase
{
    /** @var QueryAPIFilter  */
    protected $filter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filter = app()->make(TestFilter::class);
    }

    public function test_example()
    {
        $this->filter->apply(User::where('1=1'));
        $this->assertTrue(true);
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
}
