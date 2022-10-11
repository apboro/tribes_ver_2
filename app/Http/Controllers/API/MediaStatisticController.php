<?php

namespace App\Http\Controllers\API;

use App\Filters\API\MediaProductsFilter;
use App\Filters\API\MediaSalesFilter;
use App\Filters\API\MediaViewsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\MediaSalesStatRequest;
use App\Http\Resources\Statistic\MediaProductsResource;
use App\Http\Resources\Statistic\MediaSalesResource;
use App\Http\Resources\Statistic\MediaViewsResource;
use App\Repositories\Statistic\MediaProductStatisticRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaStatisticController extends StatController
{
    private MediaProductStatisticRepositoryContract $statisticRepository;

    public function __construct(MediaProductStatisticRepositoryContract $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function salesList(MediaSalesFilter $filter)
    {
        $filter->replace(['owner' => Auth::user()->id]);
        return (new MediaSalesResource($this->statisticRepository->getSales($filter)))->forApi();
    }

    public function productsList(MediaProductsFilter $filter)
    {
        $filter->replace(['owner' => Auth::user()->id]);
        return (new MediaProductsResource($this->statisticRepository->getProducts($filter)))->forApi();
    }

    public function viewsList(MediaViewsFilter $filter)
    {
        $filter->replace(['owner' => Auth::user()->id]);
        return (new MediaViewsResource($this->statisticRepository->getViews($filter)))->forApi();
    }
}
