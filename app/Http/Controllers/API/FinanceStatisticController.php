<?php

namespace App\Http\Controllers\API;

use App\Filters\API\MediaSalesFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceStatisticController extends Controller
{

    public function paymentList(MediaSalesFilter $filter)
    {
        $filter->replace(['owner' => Auth::user()->id]);
        return (new MediaSalesResource($this->statisticRepository->getSales($filter)))->forApi();
    }

}
