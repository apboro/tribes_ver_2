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
    public function getPaymentsList(array $communityIds, FinanceFilter $filters): LengthAwarePaginator;

    public function getPaymentsListForFile(array $communityIds, FinanceFilter $filter): Builder;

    public function getPaymentsCharts(array $communityIds, FinanceChartFilter $filters,string $type): ChartData;
}