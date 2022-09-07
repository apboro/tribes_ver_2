<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TeleDialogStatisticRepositoryContract
{

    public function getMembersList(int $communityId, MembersFilter $filter): LengthAwarePaginator;

    public function getJoiningMembersChart(int $communityId, MembersChartFilter $filter): ChartData;

    public function getExitingMembersChart(int $communityId, MembersChartFilter $filter): ChartData;


}