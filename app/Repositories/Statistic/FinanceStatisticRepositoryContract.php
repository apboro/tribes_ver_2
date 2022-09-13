<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface FinanceStatisticRepositoryContract
{
    public function getPaymentsList(int $communityId, FinanceFilter $filters): LengthAwarePaginator;

    public function getPaymentsCharts(int $communityId, FinanceChartFilter $filters, $type): ChartData;
}