<?php

namespace Tests\Unit\statistic;

use App\Filters\API\TeleMessagesChartFilter;
use App\Filters\API\TeleMessagesFilter;
use App\Repositories\Statistic\DTO\ChartData;
use App\Repositories\Statistic\TeleMessageStatisticRepositoryContract;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class TeleMessageStatisticRepositoryTest extends TestCase
{
    /** @var TeleMessageStatisticRepositoryContract  */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app()->make(TeleMessageStatisticRepositoryContract::class);
        $this->filter = app()->make(TeleMessagesFilter::class);
    }

    public function testGetMessageChart()
    {
        $filter = app()->make(TeleMessagesChartFilter::class);
        $chartData = $this->repository->getMessageChart([1],$filter);
        $this->assertInstanceOf(ChartData::class,$chartData);
        $exitingChartData = $this->repository->getUtilityMessageChart([1],$filter);
        $this->assertInstanceOf(ChartData::class,$exitingChartData);
        $complexChartData = $chartData->includeChart($exitingChartData,[
            'messages' => 'utility_messages'
        ]);
        $this->assertArrayHasKey('messages',$complexChartData->getValues());
        $this->assertArrayHasKey('utility',$complexChartData->getValues());
        $this->assertCount(7,$complexChartData->getValues()['messages']);
        $this->assertCount(7,$complexChartData->getValues()['utility']);
        $this->assertCount(7,$complexChartData->getMarks());
        $this->assertArrayHasKey('count_new_message',$complexChartData->getAdditions());
        $this->assertArrayHasKey('count_all_message',$complexChartData->getAdditions());
        $this->assertArrayHasKey('count_new_utility',$complexChartData->getAdditions());
    }

    public function testGetMessagesList()
    {
        $paginator = $this->repository->getMessagesList([1],$this->filter);
        $this->assertInstanceOf(LengthAwarePaginator::class,$paginator);
        $builder = $this->repository->getMessagesListForFile([1],$this->filter);
        $this->assertInstanceOf(Builder::class,$builder);
    }

}
