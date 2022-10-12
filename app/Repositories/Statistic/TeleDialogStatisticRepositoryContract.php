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

    public function getMembersList(array $communityIds, MembersFilter $filter): LengthAwarePaginator;

    public function getMembersListForFile(array $communityIds, MembersFilter $filter): Builder;

    public function getJoiningMembersChart(array $communityIds, MembersChartFilter $filter): ChartData;

    public function getExitingMembersChart(array $communityIds, MembersChartFilter $filter): ChartData;


}