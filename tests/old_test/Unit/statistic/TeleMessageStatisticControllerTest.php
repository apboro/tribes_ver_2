<?php

namespace Tests\old_test\Unit\statistic;

use App\Filters\API\TeleMessagesChartFilter;
use App\Filters\API\TeleMessagesFilter;
use App\Http\Controllers\API\TeleMessageStatisticController;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\MemberChartsResource;
use App\Http\Resources\Statistic\TelegramMessages;
use App\Repositories\Statistic\DTO\ChartData;
use App\Repositories\Statistic\TeleMessageStatisticRepository;
use App\Rules\Knowledge\OwnCommunityRule;
use App\Services\File\FileSendService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class TeleMessageStatisticControllerTest extends TestCase
{

    /**
     * @var TeleMessageStatisticController
     */
    private $controller;

    public function testMessages()
    {
        $collection = new Collection([
            [
                'telegram_user_id' => '1',
                "name" => '2',
                "nick_name" => '300',
                "text" => '300',
                "answers" => '300',
                "utility" => '300',
                "count_reactions" => '300',
                "message_date" => '300',
                "reactions" => '300',
            ],
        ]);
        $this->mock(TeleMessageStatisticRepository::class)->shouldReceive('getMessagesList')
            ->andReturn(new LengthAwarePaginator(
                $collection,
                50,
                15,
                1
            ));
        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);

        $filter = app(TeleMessagesFilter::class);
        $this->controller = app()->make(TeleMessageStatisticController::class, ['statisticRepository' => app(TeleMessageStatisticRepository::class)]);
        $result = $this->controller->messages(new TeleDialogStatRequest([], ['community_ids' => 1]), $filter);

        $this->assertInstanceOf(TelegramMessages::class, $result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey("telegram_user_id", $result->first());
        $this->assertEquals($result->first()["telegram_user_id"], "1");
    }

    public function testMessageCharts()
    {
        $this->mock(TeleMessageStatisticRepository::class,function($mock){
            /** @var $mock MockInterface */
            $mock->shouldReceive('getMessageChart')->andReturnUsing(function()
            {
                $chart = new ChartData();
                $chart->initChart(new Collection([
                    (object)['scale' => (new Carbon())->sub('5 days')->timestamp, 'messages' => 10],
                    (object)['scale' => (new Carbon())->sub('4 days')->timestamp, 'messages' => 11],
                    (object)['scale' => (new Carbon())->sub('3 days')->timestamp, 'messages' => 12],
                    (object)['scale' => (new Carbon())->sub('2 days')->timestamp, 'messages' => 13],
                    (object)['scale' => (new Carbon())->sub('1 day')->timestamp, 'messages' => 14],
                    (object)['scale' => (new Carbon())->timestamp, 'messages' => 15],
                ]));
                $chart->addAdditionParam('count_all_message', 10);

                return $chart;
            });
            $mock->shouldReceive('getUtilityMessageChart')->andReturnUsing(function()
            {
                $chart = new ChartData();
                $chart->initChart(new Collection([
                    (object)['scale' => (new Carbon())->sub('5 days')->timestamp, 'utility' => 10],
                    (object)['scale' => (new Carbon())->sub('4 days')->timestamp, 'utility' => 11],
                    (object)['scale' => (new Carbon())->sub('3 days')->timestamp, 'utility' => 12],
                    (object)['scale' => (new Carbon())->sub('2 days')->timestamp, 'utility' => 13],
                    (object)['scale' => (new Carbon())->sub('1 day')->timestamp, 'utility' => 14],
                    (object)['scale' => (new Carbon())->timestamp, 'utility' => 15],
                ]));
                $chart->addAdditionParam('count_new_utility', 10);

                return $chart;
            });
        });
        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);
        $request = request();
        $request->request->add(['filter' => ['period' => 'week']]);
        $filter = app(TeleMessagesChartFilter::class,['request'=>$request]);
        $this->controller = app(TeleMessageStatisticController::class, [
            'statisticRepository' => app(TeleMessageStatisticRepository::class),
            'fileSendService' => app(FileSendService::class),
        ]);
        $result = $this->controller->messageCharts(new TeleDialogStatRequest([], ['community_ids' => '1-3']), $filter);

        $this->assertInstanceOf(MemberChartsResource::class, $result);
    }

    public function testExportMessages()
    {

        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);

        $request = request();
        $request->request->add(['filter' => ['period' => 'day']]);
        $filter = app(TeleMessagesFilter::class,['request'=>$request]);
        $this->controller = app()->make(TeleMessageStatisticController::class, ['statisticRepository' => app(TeleMessageStatisticRepository::class)]);
        $result = $this->controller->exportMessages(new TeleDialogStatRequest([], ['community_ids' => 1, 'export_type' => 'csv']), $filter);

        $this->assertInstanceOf(StreamedResponse::class, $result);
    }
}
