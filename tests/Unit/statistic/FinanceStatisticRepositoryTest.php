<?php

namespace Tests\Unit\statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Repositories\Statistic\DTO\ChartData;
use App\Repositories\Statistic\FinanceStatisticRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class FinanceStatisticRepositoryTest extends TestCase
{
    /** @var FinanceStatisticRepositoryContract  */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app()->make(FinanceStatisticRepositoryContract::class);
        $this->filter = app()->make(FinanceFilter::class);
    }

    public function testGetPaymentsList()
    {
        $paginator = $this->repository->getPaymentsList([1],$this->filter);
        $this->assertInstanceOf(LengthAwarePaginator::class,$paginator);
        $builder = $this->repository->getPaymentsListForFile([1],$this->filter);
        $this->assertInstanceOf(Builder::class,$builder);
    }

    public function testGetPaymentsCharts()
    {
        $filter = app()->make(FinanceChartFilter::class);
        $chartData = $this->repository->getPaymentsCharts([1],$filter, 'course');

        $this->assertInstanceOf(ChartData::class,$chartData);
        $this->assertArrayHasKey('balance',$chartData->getValues());
        $this->assertCount(7,$chartData->getValues()['balance']);
        $this->assertCount(7,$chartData->getMarks());
        $this->assertArrayHasKey('course',$chartData->getAdditions());
        $this->assertArrayHasKey('total_amount',$chartData->getAdditions());

    }

}
