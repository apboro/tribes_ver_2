<?php

namespace App\Http\Controllers\APIv3\Payments;

use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiRequests\Payment\BillRequest;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\Pay\PayService;

class ApiBillController extends Controller
{
    public function makeBill(BillRequest $request): ApiResponse
    {
        $payment = PayService::billSubscription($request->subscription_id, $request->legal_id);
        if (!$payment) {     
            return ApiResponse::error('Ошибка при выставлении счета');
        }

        return ApiResponse::common(['url' => $payment->paymentUrl]);
    }
}