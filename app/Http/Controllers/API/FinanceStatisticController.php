<?php

namespace App\Http\Controllers\API;

use App\Filters\API\FinanceFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\FinancesResource;
use App\Repositories\Statistic\FinanceStatisticRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\API\FinanceStatRequest;

class FinanceStatisticController extends Controller
{

    private FinanceStatisticRepositoryContract $financeRepository;

    public function __construct(FinanceStatisticRepositoryContract $financeRepository)
    {
        $this->financeRepository = $financeRepository;
    }

    public function paymentsList(FinanceStatRequest $request, FinanceFilter $filter)
    {
        $finances = $this->financeRepository->getBuilderForFinance($request->get('community_id'),$filter);
//dd($finances);
        return (new FinancesResource($finances))->forApi();
    }


}
