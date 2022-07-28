<?php

namespace App\Repositories\Statistic;

use App\Filters\API\MediaSalesFilter;
use App\Helper\ArrayHelper;
use App\Models\Statistic\MProductSale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class MediaProductStatisticRepository implements MediaProductStatisticRepositoryContract
{

    public function getSales(MediaSalesFilter $filters): Collection
    {
        $filterData = $filters->filters();
        Log::debug("MediaProductStatisticRepository::getSales",[
            'filter' => $filterData,
        ]);
        return MProductSale::filter($filters)
            ->with('mProduct.entityObj')->with('teleUser')->with('payment')
            ->paginate(ArrayHelper::getValue($filterData,'per_page', 15),
                ['*'],
                'page',
                ArrayHelper::getValue($filterData,'page', 1));

    }
}