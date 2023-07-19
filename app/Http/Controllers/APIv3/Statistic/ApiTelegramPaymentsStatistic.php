<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Helper\ArrayHelper;
use App\Http\ApiRequests\ApiRequest;
use App\Http\ApiRequests\Statistic\ApiPaymentsMembersRequest;
use App\Http\ApiRequests\Statistic\ApiPaymentsStatisticRequest;
use App\Http\ApiResources\ApiFinanceResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Http\Resources\Statistic\FinanceResource;
use App\Http\Resources\Statistic\FinancesResource;
use App\Http\Resources\Statistic\FinancesChartsResource;
use App\Models\Community;
use App\Repositories\Statistic\FinanceStatisticRepositoryContract;
use App\Repositories\Statistic\TelegramPaymentsStatisticRepository;
use App\Services\File\FileSendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\API\FinanceStatRequest;

class ApiTelegramPaymentsStatistic
{

    private TelegramPaymentsStatisticRepository $financeRepository;
    private FileSendService $fileSendService;

    public function __construct(
        TelegramPaymentsStatisticRepository $financeRepository,
        FileSendService $fileSendService
    )
    {
        $this->financeRepository = $financeRepository;
        $this->fileSendService = $fileSendService;
    }

    public function paymentsCharts(ApiPaymentsStatisticRequest $request, FinanceChartFilter $filter)
    {
        $chartPaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request),$filter, $type = 'all');
        $coursePaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request),$filter, $type = 'course');
        $donatePaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request),$filter, $type = 'donate');
        $tariffPaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request),$filter, $type = 'tariff');

        $chartPaymentsData->includeChart($coursePaymentsData,['balance' => 'course_balance']);
        $chartPaymentsData->includeChart($donatePaymentsData,['balance' => 'donate_balance']);
        $chartPaymentsData->includeChart($tariffPaymentsData,['balance' => 'tariff_balance']);

        return (new FinancesChartsResource($chartPaymentsData));
    }

    public function paymentsList(ApiPaymentsMembersRequest $request, FinanceFilter $filter)
    {
        $payments = $this->financeRepository->getPaymentsList($filter);

        return ApiResponse::listPagination(['Access-Control-Expose-Headers'=>'Items-Count', 'Items-Count'=>$payments->count()])->items(ApiFinanceResource::collection($payments->get()));
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
                'attribute' => 'status',
                'title' => 'Оплата'
            ],
            [
                'attribute' => 'type',
                'title' => 'Тип транзакции'
            ],
            [
                'attribute' => 'buy_date',
                'title' => 'Дата оплаты'
            ],
            [
                'attribute' => 'amount',
                'title' => 'Сумма'
            ],
        ];
        $type = $request->get('export_type');
        $membersBuilder = $this->financeRepository->getPaymentsListForFile($filter);
        return $this->fileSendService->sendFile($membersBuilder, $columnNames,FinanceResource::class,$type,'members');
    }

    protected function getCommunityIds(ApiRequest $request): array
    {
        $community_ids = $request->get('community_ids');
        if (!$community_ids)
        {
            $community_ids = 'all';
        }
        if ($community_ids == 'all') {
            $communityIds = ArrayHelper::getColumn(Community::where('owner', Auth::user()->id)->get(),'id');
        } else {
            $communityIds = explode('-', $community_ids);
            $communityIds = array_filter($communityIds);
            if (empty($communityIds)) {
                abort(403);
            }
        }
        return $communityIds;
    }
}
