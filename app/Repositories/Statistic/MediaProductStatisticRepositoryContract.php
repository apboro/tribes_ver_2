<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MediaSalesFilter;
use Illuminate\Database\Eloquent\Collection;

interface MediaProductStatisticRepositoryContract
{
    public function getSales(MediaSalesFilter $filters): Collection;

}