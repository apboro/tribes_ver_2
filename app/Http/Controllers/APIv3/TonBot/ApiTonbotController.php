<?php

namespace App\Http\Controllers\APIv3\TonBot;

use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiRequests\TonBot\ApiAddCardRequest;
use App\Http\ApiRequests\TonBot\ApiPaymentHistoryRequest;
use App\Http\ApiRequests\TonBot\ApiPaymentRequest;
use App\Http\ApiRequests\TonBot\ApiTgUserRequest;
use App\Http\Controllers\Controller;
use App\Models\TonbotPayment;
use App\Services\TonBot\PaymentCards;
use App\Services\TonBot\Payments as TonbotPaymentsService;

class ApiTonbotController extends Controller
{
    public function addCard(ApiAddCardRequest $request): ApiResponse
    {
        return ApiResponse::common(PaymentCards::addCard($request->telegram_id, $request->phone));
    }

    public function deleteCard(ApiTgUserRequest $request): ApiResponse
    {
        return ApiResponse::common(PaymentCards::deleteCard($request->telegram_id));
    }

    public function payment(ApiPaymentRequest $request): ApiResponse
    {
        $payment = TonbotPaymentsService::create($request->telegram_receiver_id, 
                                            $request->telegram_sender_id, 
                                            $request->amount, 
                                            $request->success_url ?? '');

        if ($payment && $payment->paymentUrl) {
            return ApiResponse::common(['status' => 'success', 'payment_id' => $payment->id, 'url' => $payment->paymentUrl]);
        } else {
            return ApiResponse::common(['status' => 'error', 'message' => 'Платеж не создан']);
        }
    }

    public function paymentHistory(ApiPaymentHistoryRequest $request): ApiResponse
    {
        if (!$request->input('telegram_receiver_id') && !$request->input('telegram_sender_id')) {
            return ApiResponse::common(['status' => 'error', 'message' => 'Необходимо указать получателя или отправителя.']);
        }
        $history = TonbotPayment::history($request->input('telegram_receiver_id'), 
                                $request->input('telegram_sender_id'));

        return ApiResponse::common(['status' => 'success', 'history' => $history]);
    }
    
}