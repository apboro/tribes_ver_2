<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Filters\API\FinanceChartFilter;
use App\Filters\API\FinanceFilter;
use App\Helper\ArrayHelper;
use App\Http\ApiRequests\ApiRequest;
use App\Http\ApiRequests\Statistic\ApiExportFinancesRequest;
use App\Http\ApiRequests\Statistic\ApiPaymentsMembersRequest;
use App\Http\ApiRequests\Statistic\ApiPaymentsStatisticRequest;
use App\Http\ApiResources\ApiFinanceResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Resources\Statistic\FinancesChartsResource;
use App\Models\Community;
use App\Models\User;
use App\Repositories\Statistic\TelegramPaymentsStatisticRepository;
use App\Services\File\FilePrepareService;
use Illuminate\Support\Facades\Auth;

class ApiTelegramPaymentsStatistic
{

    private TelegramPaymentsStatisticRepository $financeRepository;

    private FilePrepareService $filePrepareService;

    public function __construct(
        TelegramPaymentsStatisticRepository $financeRepository,
        FilePrepareService                  $filePrepareService
    )
    {
        $this->financeRepository = $financeRepository;
        $this->filePrepareService = $filePrepareService;
    }

    public function paymentsCharts(ApiPaymentsStatisticRequest $request, FinanceChartFilter $filter)
    {
        $chartPaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request), $filter, $type = 'all');
        $coursePaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request), $filter, $type = 'course');
        $donatePaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request), $filter, $type = 'donate');
        $tariffPaymentsData = $this->financeRepository->getPaymentsCharts($this->getCommunityIds($request), $filter, $type = 'tariff');

        $chartPaymentsData->includeChart($coursePaymentsData, ['balance' => 'course_balance']);
        $chartPaymentsData->includeChart($donatePaymentsData, ['balance' => 'donate_balance']);
        $chartPaymentsData->includeChart($tariffPaymentsData, ['balance' => 'tariff_balance']);

        return (new FinancesChartsResource($chartPaymentsData));
    }

    public function paymentsList(ApiPaymentsMembersRequest $request, FinanceFilter $filter)
    {
        $payments = $this->financeRepository->getPaymentsList($filter);

        return ApiResponse::listPagination(['Access-Control-Expose-Headers' => 'Items-Count', 'Items-Count' => $payments->count()])->items(ApiFinanceResource::collection($payments->get()));
    }

    public function exportPayments(ApiExportFinancesRequest $request, FinanceFilter $filter)
    {
        $names = [
            [
                'attribute' => 'first_name',
                'title' => 'Имя'
            ],
            [
                'attribute' => 'user_name',
                'title' => 'Никнейм'
            ],
            [
                'attribute' => 'payable_title',
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
        $prepare_result = $this->filePrepareService->prepareFile(
            $this->financeRepository->getPaymentsList($filter),
            $names,
            ApiFinanceResource::class,
            $request->get('type', 'csv'),
            'users'
        );
        if (!$prepare_result['result']) {
            return ApiResponse::error($prepare_result['message']);
        }
        return ApiResponse::common([
            'file_path' => $prepare_result['file_path']
        ]);
    }

    protected function getCommunityIds(ApiRequest $request): array
    {
        $community_ids = $request->get('community_ids');
        if (!$community_ids) {
            $community_ids = 'all';
        }
        if ($community_ids == 'all') {
            $communityIds = ArrayHelper::getColumn(Community::where('owner', Auth::user()->id)->get(), 'id');
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
