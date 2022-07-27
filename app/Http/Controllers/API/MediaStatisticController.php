<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Statistic\MediaProductStatisticRepositoryContract;
use Illuminate\Http\Request;

class MediaStatisticController extends Controller
{
    private MediaProductStatisticRepositoryContract $statisticRepository;

    public function __construct(MediaProductStatisticRepositoryContract $statisticRepository)
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function salesList(Request $request)
    {

    }

    public function productsList(Request $request)
    {

    }

    public function viewsList(Request $request)
    {

    }
}
