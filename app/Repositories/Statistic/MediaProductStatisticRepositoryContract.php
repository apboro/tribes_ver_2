<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MediaProductsFilter;
use App\Filters\API\MediaSalesFilter;
use App\Filters\API\MediaViewsFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MediaProductStatisticRepositoryContract
{
    public function getSales(MediaSalesFilter $filters): LengthAwarePaginator;

    public function getProducts(MediaProductsFilter $filters): LengthAwarePaginator;

    public function getViews(MediaViewsFilter $filters): LengthAwarePaginator;

}