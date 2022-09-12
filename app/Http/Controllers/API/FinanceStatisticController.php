<?php

namespace App\Http\Controllers\API;

use App\Filters\API\FinanceFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\FinancesResource;
use App\Http\Resources\Statistic\FinancesChartsResource;
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

    public function paymentsCharts(FinanceStatRequest $request, FinanceFilter $filter)
    {
        $chartPaymentsData = $this->financeRepository->getPaymentsCharts($request->get('community_id'),$filter, $type = 'all');
        $coursePaymentsData = $this->financeRepository->getPaymentsCharts($request->get('community_id'),$filter, $type = 'course');
        $donatePaymentsData = $this->financeRepository->getPaymentsCharts($request->get('community_id'),$filter, $type = 'donate');
        $tariffPaymentsData = $this->financeRepository->getPaymentsCharts($request->get('community_id'),$filter, $type = 'tariff');

        $chartPaymentsData->includeChart($coursePaymentsData,['balance' => 'course_balance']);
        $chartPaymentsData->includeChart($donatePaymentsData,['balance' => 'donate_balance']);
        $chartPaymentsData->includeChart($tariffPaymentsData,['balance' => 'tariff_balance']);

        return (new FinancesChartsResource($chartPaymentsData));
    }

    public function paymentsList(FinanceStatRequest $request, FinanceFilter $filter)
    {
        $payments = $this->financeRepository->getPaymentsList($request->get('community_id'),$filter);

        return (new FinancesResource($payments))->forApi();
    }
}
