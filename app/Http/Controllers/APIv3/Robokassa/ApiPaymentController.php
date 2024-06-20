<?php

namespace App\Http\Controllers\APIv3\Robokassa;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentRedirectService;
use Illuminate\Http\Request;

class ApiPaymentController extends Controller
{
    public function handleSuccess(Request $request)
    {
        $data = $request->all();

        /* @var $payment Payment */
        $payment = Payment::find($data['InvId']);

        if (!$payment || $payment->status !== PaymentStatus::CONFIRMED) {
            return response()->redirectTo(config('app.frontend_url'));
        }

        $redirectUrl = PaymentRedirectService::buildSuccessUrl($payment);

        return response()->redirectTo($redirectUrl);
    }

    public function handleFail(Request $request)
    {
        $data = $request->all();

        /* @var $payment Payment */
        $payment = Payment::find($data['InvId']);

        if (!$payment) {
            return response()->redirectTo(config('app.frontend_url'));
        }

        return response()->redirectTo(config('app.frontend_url'));
    }
}
