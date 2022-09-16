<?php

namespace App\Http\Controllers\API;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\FinanceResource;
use App\Http\Resources\Statistic\FinancesResource;
use App\Http\Resources\Statistic\FinancesChartsResource;
use App\Repositories\Statistic\FinanceStatisticRepositoryContract;
use App\Services\File\FileSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\API\FinanceStatRequest;

class FinanceStatisticController extends Controller
{

    private FinanceStatisticRepositoryContract $financeRepository;
    private FileSendService $fileSendService;

    public function __construct(
        FinanceStatisticRepositoryContract $financeRepository,
        FileSendService $fileSendService
    )
    {
        $this->financeRepository = $financeRepository;
        $this->fileSendService = $fileSendService;
    }

    public function paymentsCharts(FinanceStatRequest $request, FinanceChartFilter $filter)
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

    public function exportPayments(TeleDialogStatRequest $request, FinanceFilter $filter)
    {

        $columnNames = [
            [
                'attribute' => 'first_name',
                'title' => 'Имя'
            ],
            [
                'attribute' => 'tele_login',
                'title' => 'Никнейм'
            ],
            [
                'attribute' => 'buy_date',
                'title' => 'Дата оплаты'
            ],

            [
                'attribute' => 'type',
                'title' => 'Тип транзакции'
            ],
            [
                'attribute' => 'status',
                'title' => 'Статус транзакции'
            ],
            [
                'attribute' => 'amount',
                'title' => 'Сумма'
            ],
        ];
        $type = $request->get('export_type');
        $membersBuilder = $this->financeRepository->getPaymentsListForFile($request->get('community_id'),$filter);

        return $this->fileSendService->sendFile($membersBuilder, $columnNames,FinanceResource::class,$type,'members');
    }
}
