<?php

namespace Tests\Unit\statistic;

use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Http\Controllers\API\TeleDialogStatisticController;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\MemberChartsResource;
use App\Http\Resources\Statistic\MembersResource;
use App\Repositories\Statistic\DTO\ChartData;
use App\Repositories\Statistic\TeleDialogStatisticRepository;
use App\Repositories\Statistic\TeleDialogStatisticRepositoryContract;
use App\Rules\Knowledge\OwnCommunityRule;
use App\Services\File\FileSendService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;


class TeleDialogStatisticControllerTest extends TestCase
{
    /** @var TeleDialogStatisticController */
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = app()->make(TeleDialogStatisticController::class, ['statisticRepository' => $this->mock(TeleDialogStatisticRepository::class)]);
    }


    public function testMembers()
    {
        $collection = new Collection([
            [
                "chat_id" => '1',
                "tele_id" => '2',
                "user_utility" => '300',
                "name" => 'Ivan Ivanov',
                "nick_name" => 'Ivanov',
                "accession_date" => '1662435570',
                'exit_date' => '1662482370',
                'c_messages' => '20',
                'c_got_reactions' => '25',
                'c_put_reactions' => '60',
            ],
        ]);
        $this->mock(TeleDialogStatisticRepository::class)->shouldReceive('getMembersList')
            ->andReturn(new LengthAwarePaginator(
                $collection,
                50,
                15,
                1
            ));
        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);

        $filter = app(MembersFilter::class);
        $this->controller = app()->make(TeleDialogStatisticController::class, ['statisticRepository' => app(TeleDialogStatisticRepository::class)]);
        $result = $this->controller->members(new TeleDialogStatRequest([], ['community_ids' => 1]), $filter);

        $this->assertInstanceOf(MembersResource::class, $result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey("chat_id", $result->first());
        $this->assertEquals($result->first()["chat_id"], "1");
    }

    public function testMemberCharts()
    {
        $chart = new ChartData();
        $chart->initChart(new Collection([
            (object)['scale' => (new Carbon())->sub('5 days')->timestamp, 'users' => 10],
            (object)['scale' => (new Carbon())->sub('4 days')->timestamp, 'users' => 11],
            (object)['scale' => (new Carbon())->sub('3 days')->timestamp, 'users' => 12],
            (object)['scale' => (new Carbon())->sub('2 days')->timestamp, 'users' => 13],
            (object)['scale' => (new Carbon())->sub('1 day')->timestamp, 'users' => 14],
            (object)['scale' => (new Carbon())->timestamp, 'users' => 15],
        ]));
        $chart->addAdditionParam('count_exit_users', 10);
        $this->mock(TeleDialogStatisticRepository::class,function($mock) use ($chart){
            $mock->shouldReceive('getJoiningMembersChart')->andReturn(clone $chart);
            $mock->shouldReceive('getExitingMembersChart')->andReturn(clone $chart);
        });
        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);

        $filter = app(MembersChartFilter::class);
        $this->controller = app()->make(TeleDialogStatisticController::class, ['statisticRepository' => app(TeleDialogStatisticRepository::class)]);
        $result = $this->controller->memberCharts(new TeleDialogStatRequest([], ['community_ids' => '1-3']), $filter);

        $this->assertInstanceOf(MemberChartsResource::class, $result);

        $this->assertArrayHasKey('users',$result->toArray(new Request()));
        $this->assertArrayHasKey('exit_users',$result->toArray(new Request()));
        $this->assertCount(6, $result->toArray(new Request())['users']);
    }

    public function testExportMembers()
    {
        $builder = DB::table('users');
        $this->mock(TeleDialogStatisticRepository::class)->shouldReceive('getMembersListForFile')
            ->andReturn($builder);
        $this->mock(OwnCommunityRule::class)->shouldReceive('passes')
            ->andReturn(true);
        $this->mock(FileSendService::class)->shouldReceive('sendFile')
            ->andReturn(new StreamedResponse(function(){ return false;}, 200, []));
        $filter = app(MembersFilter::class);
        $controller = app()->make(TeleDialogStatisticController::class, [
            'statisticRepository' => app(TeleDialogStatisticRepository::class),
            'fileSendService' => app(FileSendService::class),
        ]);
        $result = $controller->exportMembers(new TeleDialogStatRequest([], ['community_ids' => '1-3', 'export_type' => 'csv']), $filter);
        $this->assertInstanceOf(StreamedResponse::class, $result);
    }
}
