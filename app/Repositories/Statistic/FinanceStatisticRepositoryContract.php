<?php

namespace App\Repositories\Statistic;

use App\Filters\API\FinanceFilter;
use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface FinanceStatisticRepositoryContract
{
    public function getBuilderForFinance(int $communityId, FinanceFilter $filters): ChartData;
}