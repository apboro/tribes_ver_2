<?php

namespace App\Http\Controllers\APIv3\Manager;

use App\Http\ApiRequests\Admin\ApiAdminCustomersRequest;
use App\Http\ApiRequests\Admin\ApiAdminPaymentListRequest;
use App\Http\ApiResources\Admin\AdminCustomerCollection;
use App\Http\ApiResources\Admin\AdminPaymentCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\PaymentsFilter;
use App\Models\Payment;


class ApiAdminPaymentController extends Controller
{
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
            ])->items((new AdminPaymentCollection($payments->skip($request->offset)->take($request->limit)->orderBy('id')->get()))->toArray($request));

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
}
