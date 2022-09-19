<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;

interface FinanceStatisticRepositoryContract
{
    public function getPaymentsList(int $communityId, FinanceFilter $filters): LengthAwarePaginator;

    public function getPaymentsListForFile(int $communityId, FinanceFilter $filter): Builder;

    public function getPaymentsCharts(int $communityId, FinanceChartFilter $filters,string $type): ChartData;
}