<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MembersChartFilter;
use App\Filters\API\MembersFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TeleDialogStatisticRepositoryContract
{

    public function getMembersList(int $communityId, MembersFilter $filter): LengthAwarePaginator;

    public function getMembersListForFile(int $communityId, MembersFilter $filter): Builder;

    public function getJoiningMembersChart(int $communityId, MembersChartFilter $filter): ChartData;

    public function getExitingMembersChart(int $communityId, MembersChartFilter $filter): ChartData;


}