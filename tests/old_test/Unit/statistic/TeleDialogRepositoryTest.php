<?php

namespace Tests\old_test\Unit\statistic;

use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Repositories\Statistic\DTO\ChartData;
use App\Repositories\Statistic\TeleDialogStatisticRepositoryContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class TeleDialogRepositoryTest extends TestCase
{
    /** @var TeleDialogStatisticRepositoryContract  */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app()->make(TeleDialogStatisticRepositoryContract::class);
        $this->filter = app()->make(MembersFilter::class);
    }


    public function testMembersList()
    {
        $paginator = $this->repository->getMembersList([1],$this->filter);
        $this->assertInstanceOf(LengthAwarePaginator::class,$paginator);
        $builder = $this->repository->getMembersListForFile([1],$this->filter);
        $this->assertInstanceOf(Builder::class,$builder);
        //print_r($builder->toSql());
    }

    public function testMembersChart()
    {
        $filter = app()->make(MembersChartFilter::class);
        $chartData = $this->repository->getJoiningMembersChart([1],$filter);
        $this->assertInstanceOf(ChartData::class,$chartData);
        $exitingChartData = $this->repository->getExitingMembersChart([1],$filter);
        $this->assertInstanceOf(ChartData::class,$exitingChartData);
        $complexChartData = $chartData->includeChart($exitingChartData,[
             'users' => 'exit_users'
        ]);
        $this->assertArrayHasKey('users',$complexChartData->getValues());
        $this->assertArrayHasKey('exit_users',$complexChartData->getValues());
        $this->assertCount(7,$complexChartData->getValues()['users']);
        $this->assertCount(7,$complexChartData->getValues()['exit_users']);
        $this->assertCount(7,$complexChartData->getMarks());
        $this->assertArrayHasKey('count_join_users',$complexChartData->getAdditions());
        $this->assertArrayHasKey('all_users',$complexChartData->getAdditions());
        $this->assertArrayHasKey('count_exit_users',$complexChartData->getAdditions());

    }
}
