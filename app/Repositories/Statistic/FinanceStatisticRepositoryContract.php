<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface FinanceStatisticRepositoryContract
{
    public function getPaymentsList(int $communityId, FinanceFilter $filters): LengthAwarePaginator;

    public function getPaymentsCharts(int $communityId, FinanceFilter $filters, $type): ChartData;
}