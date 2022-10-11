<?php

namespace Tests\Unit\statistic;

use App\Filters\API\MembersFilter;
use App\Http\Controllers\API\TeleDialogStatisticController;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\MembersResource;
use App\Repositories\Statistic\TeleDialogStatisticRepository;
use App\Repositories\Statistic\TeleDialogStatisticRepositoryContract;
use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;


class TeleDialogStatisticControllerTest extends TestCase
{
    /** @var TeleDialogStatisticController */
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = app()->make(TeleDialogStatisticController::class,['statisticRepository' => $this->mock(TeleDialogStatisticRepository::class)]);
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
        $this->controller = app()->make(TeleDialogStatisticController::class,['statisticRepository' => app(TeleDialogStatisticRepository::class)]);
        $result = $this->controller->members(new TeleDialogStatRequest([],['community_id' => 1]), $filter);

        $this->assertInstanceOf(MembersResource::class, $result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey("chat_id",$result->first());
        $this->assertEquals($result->first()["chat_id"],"1");
    }

    public function testMemberCharts()
    {
        $this->assertTrue(true);
    }

    public function testExportMembers()
    {
        $this->assertTrue(true);
    }
}
