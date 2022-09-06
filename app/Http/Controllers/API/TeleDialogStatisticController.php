<?php

namespace App\Http\Controllers\API;

use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\MemberChartsResource;
use App\Http\Resources\Statistic\MembersResource;
use App\Repositories\Statistic\TeleDialogStatisticRepositoryContract;

class TeleDialogStatisticController extends Controller
{
    private TeleDialogStatisticRepositoryContract $statisticRepository;

    public function __construct(TeleDialogStatisticRepositoryContract $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function members(TeleDialogStatRequest $request, MembersFilter $filter)
    {
        $members = $this->statisticRepository->getMembersList($request->get('community_id'),$filter);

        return (new MembersResource($members))->forApi();
    }

    public function memberCharts(TeleDialogStatRequest $request, MembersChartFilter $filter)
    {
        $chartJoiningData = $this->statisticRepository->getJoiningMembersChart($request->get('community_id'),$filter);
        //$chartExitingData = $this->statisticRepository->getExitingMembersChart($request->get('community_id'),$filter);

        return (new MemberChartsResource($chartJoiningData));
    }
}