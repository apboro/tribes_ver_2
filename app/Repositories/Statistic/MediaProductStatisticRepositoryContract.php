<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MediaProductsFilter;
use App\Filters\API\MediaSalesFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MediaProductStatisticRepositoryContract
{
    public function getSales(MediaSalesFilter $filters): LengthAwarePaginator;

    public function getProducts(MediaProductsFilter $filters): LengthAwarePaginator;

}