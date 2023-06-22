<?php

namespace App\Http\Controllers\APIv3\Manager;

use App\Exceptions\StatisticException;
use App\Http\ApiRequests\Admin\ApiAdminCustomersRequest;
use App\Http\ApiRequests\Admin\ApiAdminPaymentListRequest;
use App\Http\ApiResources\Admin\AdminCustomerCollection;
use App\Http\ApiResources\Admin\AdminPaymentCollection;
use App\Http\ApiResources\Admin\AdminPaymentResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\PaymentsFilter;
use App\Http\Requests\ApiPaymentManagerExportRequest;
use App\Models\Payment;
use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ApiAdminPaymentController extends Controller
{

    private FileSendService $fileSendService;

    public function __construct(
        FileSendService $fileSendService
    )
    {

        $this->fileSendService = $fileSendService;
    }

    /**
     * @param ApiAdminPaymentListRequest $request
     * @param PaymentsFilter $filter
     * @return ApiResponse
     */
    public function list(ApiAdminPaymentListRequest $request, PaymentsFilter $filter): ApiResponse
    {
        /** @var Payment $customers */
        $payments = Payment::whereNotNull('status')->
        filter($filter)->
        paginate(25);
        $payments->load('community');
        return ApiResponse::list()->items(AdminPaymentCollection::make($payments)->toArray($request));
    }

    /**
     * @param ApiAdminCustomersRequest $request
     * @return ApiResponse
     */
    public function customers(ApiAdminCustomersRequest $request): ApiResponse
    {
        /** @var Payment $customers */
        $customers = Payment::all()->unique('user_id');
        return ApiResponse::list()->items(AdminCustomerCollection::make($customers)->toArray($request));
    }

    /**
     * @param ApiPaymentManagerExportRequest $request
     * @return StreamedResponse
     * @throws StatisticException
     */
    public function export(ApiPaymentManagerExportRequest $request)
    {
        $names = [
            [
                'title' => 'Номер',
                'attribute' => 'order_id',
            ],
            [
                'title' => 'Сообщество',
                'attribute' => 'community',
            ],
            [
                'title' => 'Баланс',
                'attribute' => 'add_balance',
            ],
            [
                'title' => 'От',
                'attribute' => 'from',
            ],
            [
                'title' => 'Статус',
                'attribute' => 'status',
            ],
            [
                'title' => 'Тип',
                'attribute' => 'type',
            ],
        ];
        return $this->fileSendService->sendFile(
            Payment::query(),
            $names,
            AdminPaymentResource::class,
            $request->get('type', 'csv'),
            'payments'
        );
    }
}
