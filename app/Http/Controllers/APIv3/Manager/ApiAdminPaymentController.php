<?php

namespace App\Http\Controllers\APIv3\Manager;

use App\Exceptions\StatisticException;
use App\Http\ApiRequests\Admin\ApiAdminCustomersRequest;
use App\Http\ApiRequests\Admin\ApiAdminPaymentListRequest;
use App\Http\ApiResources\Admin\AdminCustomerCollection;
use App\Http\ApiResources\Admin\AdminPaymentCollection;
use App\Http\ApiResources\Admin\AdminPaymentResource;
use App\Http\ApiResources\Admin\UserForManagerResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\PaymentsFilter;
use App\Http\Requests\ApiPaymentManagerExportRequest;
use App\Models\Payment;
use App\Models\User;
use App\Services\File\FIlePrepareService;
use App\Services\File\FileSendService;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ApiAdminPaymentController extends Controller
{

    private FIlePrepareService $FIlePrepareService;

    public function __construct(
        FIlePrepareService $FIlePrepareService
    )
    {

        $this->FIlePrepareService = $FIlePrepareService;
    }


    /**
     * @param ApiAdminPaymentListRequest $request
     * @param PaymentsFilter $filter
     * @return ApiResponse
     */
    public function list(ApiAdminPaymentListRequest $request, PaymentsFilter $filter): ApiResponse
    {
        /** @var Payment $customers */
        $payments = Payment::with(['community'])->whereNotNull('status')->filter($filter);
        $count = $payments->count();
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((new AdminPaymentCollection($payments->skip($request->offset)->take($request->limit ?? 25)->orderBy('id')->get()))->toArray($request));

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
     * @return ApiResponse
     */
    public function export(ApiPaymentManagerExportRequest $request): ApiResponse
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
        $prepare_result = $this->FIlePrepareService->prepareFile(
            Payment::query(),
            $names,
            AdminPaymentResource::class,
            $request->get('type', 'csv'),
            'payments'
        );
        if (!$prepare_result['result']) {
            return ApiResponse::error($prepare_result['message']);
        }
        return ApiResponse::common([
            'file_path' => $prepare_result['file_path']
        ]);
    }

}
