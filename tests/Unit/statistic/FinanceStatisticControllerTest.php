<?php

namespace Tests\Unit\statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\FinancesChartsResource;
use App\Http\Resources\Statistic\FinancesResource;
use App\Repositories\Statistic\DTO\ChartData;
use App\Repositories\Statistic\FinanceStatisticRepository;
use App\Rules\Knowledge\OwnCommunityRule;
use App\Services\File\FileSendService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;
use App\Http\Controllers\API\FinanceStatisticController;

class FinanceStatisticControllerTest extends TestCase
{
    /** @var FinanceStatisticController */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);
        $this->mock(FileSendService::class)->shouldReceive('sendFile')
            ->andReturn(new StreamedResponse(function(){ return false;}, 200, []));

    }

    public function testPaymentsList()
    {
        $this->controller = app(FinanceStatisticController::class, [
            'statisticRepository' => app(FinanceStatisticRepository::class),
            'fileSendService' => app(FileSendService::class),
        ]);

        $request = request();
        $request->request->add(['filter' => ['period' => 'day', 'export_type' => 'csv']]);
        $result = $this->controller->paymentsList(
            new TeleDialogStatRequest([], ['community_ids' => '1-3', 'export_type' => 'csv']),
            app(FinanceFilter::class,['request'=>$request])
        );

        $this->assertInstanceOf(FinancesResource::class, $result);
    }

    public function testExportPayments()
    {
        $this->controller = app(FinanceStatisticController::class, [
            'statisticRepository' => app(FinanceStatisticRepository::class),
            'fileSendService' => app(FileSendService::class),
        ]);

        $request = request();
        $request->request->add(['filter' => ['period' => 'day']]);
        $result = $this->controller->exportPayments(
            new TeleDialogStatRequest([], ['community_ids' => '1-3', 'export_type' => 'csv']),
            app(FinanceFilter::class,['request'=>$request])
        );

        $this->assertInstanceOf(StreamedResponse::class, $result);
    }

    public function testMemberCharts()
    {

        $this->mock(FinanceStatisticRepository::class,function($mock){
            /** @var $mock MockInterface */
            $mock->shouldReceive('getPaymentsCharts')->andReturnUsing(function()
            {
                $chart = new ChartData();
                $chart->initChart(new Collection([
                    (object)['scale' => (new Carbon())->sub('5 days')->timestamp, 'balance' => 10],
                    (object)['scale' => (new Carbon())->sub('4 days')->timestamp, 'balance' => 11],
                    (object)['scale' => (new Carbon())->sub('3 days')->timestamp, 'balance' => 12],
                    (object)['scale' => (new Carbon())->sub('2 days')->timestamp, 'balance' => 13],
                    (object)['scale' => (new Carbon())->sub('1 day')->timestamp, 'balance' => 14],
                    (object)['scale' => (new Carbon())->timestamp, 'balance' => 15],
                ]));
                $chart->addAdditionParam('count_exit_users', 10);

                return $chart;
            });
        });
        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);
        $request = request();
        $request->request->add(['filter' => ['period' => 'week']]);
        $filter = app(FinanceChartFilter::class,['request'=>$request]);
        $this->controller = app(FinanceStatisticController::class, [
            'statisticRepository' => app(FinanceStatisticRepository::class),
            'fileSendService' => app(FileSendService::class),
        ]);
        $result = $this->controller->paymentsCharts(new TeleDialogStatRequest([], ['community_ids' => '1-3']), $filter);

        $this->assertInstanceOf(FinancesChartsResource::class, $result);
        $this->assertArrayHasKey('course_balance',$result->toArray($request));
        $this->assertArrayHasKey('donate_balance',$result->toArray($request));
        $this->assertArrayHasKey('tariff_balance',$result->toArray($request));
        $this->assertArrayHasKey('balance',$result->toArray($request));

    }
}
