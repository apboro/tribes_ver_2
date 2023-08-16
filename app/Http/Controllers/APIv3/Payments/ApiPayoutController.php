<?php

namespace App\Http\Controllers\APIv3\Payments;

use App\Http\Controllers\Controller;
use App\Http\ApiRequests\Payment\PayOutRequest;
use App\Http\ApiRequests\Payment\CardAndAccumulationForPayoutRequest;
use App\Models\Accumulation;
use App\Services\Tinkoff\TinkoffE2C;
use App\Services\TinkoffE2C as TinkoffE2CCard;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\ApiResponses\ApiResponse;

class ApiPayoutController extends Controller
{
 
    public TinkoffE2C $e2c;
    public TinkoffE2CCard $etcCard;

    public function __construct()
    {
        $this->e2c = new TinkoffE2C();
        $this->etcCard = new TinkoffE2CCard();
    }

    /**
     * Вывод списка карт и активного Accumulation
     */
    public function cardAndAccumulationForPayout(CardAndAccumulationForPayoutRequest $request)
    {
        $cardsList = [];
        $this->etcCard->GetCardList(Auth::user()->getCustomerKey());
        $cards = $this->etcCard->response();
        if (isset($cards['data']) && is_array($cards['data'])) {
            foreach ($cards['data'] as $card){
                $cardsList[] = ['CardId' => $card->CardId ?? null,
                                'Pan' => $card->Pan ?? null,
                                'Status' => $card->Status ?? null 
                                ];
            }
        } 

        /** @var Accumulation $accumulation */
        $accumulation = Accumulation::select('SpAccumulationId', 'amount')
                                    ->where('user_id', Auth::user()->id)
                                    ->where('status', 'active')
                                    ->first();

        if (isset($accumulation->amount)) {
            $accumulation->amount /= 100;
        }

        return ApiResponse::common(['cards' => $cardsList, 'accumulation' => $accumulation]);
    }

    public function payout(PayOutRequest $request)
    {
        Log::debug('Payout method start');
        $accumulationId = (int)$request['accumulationId'];
        $accumulation = Accumulation::where('SpAccumulationId', $accumulationId)
                                    ->where('status', '!=', 'closed')->first();
        if(!$accumulation){
            Log::debug('Accumulation not found!');
            return response()->json([
                'status' => 'error',
                'message' => 'Накопление не найдено',
            ]);
        }

        $minPayout = config('tinkoff.minPayout', 5) * 1;
        $maxPayout = config('tinkoff.maxPayout', 100000) * 100;
        if (($accumulation->amount < $minPayout) || ($accumulation->amount > $maxPayout)){
            return response()->json([
                'status' => 'error',
                'message' => 'Вывод возможен от '. config('tinkoff.minPayout', 500). ' до ' . config('tinkoff.maxPayout', 100000) . ' рублей'
            ]);
        }

        Log::debug('Получаем номер карты для сохранения');
        // Получаем номер карты для сохранения
        $this->etcCard->GetCardList(Auth::user()->getCustomerKey());
        $cards = $this->etcCard->response();

        $cardNumber = null;
        if (isset($cards['data']) && is_array($cards['data'])) {
            $cardNumber = $cards['data'][0]->Pan ?? null;
        } 
        Log::debug(json_encode($cards));
        if (!$cardNumber) {
            return response()->json([
                'status' => 'error',
                'message' => 'Привязанная карта не найдена.'
            ]);
        }

        Log::debug('Инициализация вывода');
        $orderId = Auth::user()->id . date("_md_s");
        $params = [
            'Amount' => $accumulation->amount,
            'OrderId' => $orderId,
            'CardId' => $request['cardId'],
            'DATA' => [
                'SpAccumulationId' => $accumulationId,
                'SpFinalPayout' => true
            ]
        ];
        $this->e2c->init($params);

        $resp = $this->e2c->response();

        if(isset($resp['data']->Success) && $resp['data']->Success && isset($resp['data']->Status) && $resp['data']->Status == 'CHECKED'){
            Log::debug('Payout status CHECKED');
            $paymentId = $resp['data']->PaymentId;

            $payOutAmount = (int)$resp['data']->Amount;

            $this->e2c->Pay($paymentId);
            $resp = $this->e2c->response();

            if(isset($resp['data']->Success) && $resp['data']->Success && isset($resp['data']->Status) && $resp['data']->Status == 'COMPLETED'){
                Log::debug('Payout status COMPLETED');

                // Успешная выплата
                $accumulation->status = 'closed';
                $accumulation->save();

                $payment = new Payment();
                $payment->type = 'payout';
                $payment->amount = $payOutAmount;
                $payment->status = $resp['data']->Status;
                $payment->SpAccumulationId = $accumulationId;
                $payment->user_id = Auth::user()->id;
                $payment->author = Auth::user()->author()->id ?? null;
                $payment->from = Auth::user()->name ?? 'Анонимный получаетль';
                $payment->community_id = Auth::user()->communities()->first()->id ?? null;
                $payment->add_balance = 0;
                $payment->OrderId = $orderId;
                $payment->paymentId = $resp['data']->PaymentId;
                $payment->card_number = $cardNumber;
                $payment->save();

                return response()->json([
                    'status' => 'ok',
                    'message' => 'Выплата на карту осуществлена'
                ]);

            } else {
                // Неуспешная выплата
                Log::debug('Payout status ERROR');
                $message = $resp['data']->Message ?? null;
                $details = $resp['data']->Details ?? null;

                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'details' => $details,
                ]);
            }
        } else {
            Log::debug('Payout status NOT CHECKED');
            $message = $resp['data']->Message ?? null;
            $details = $resp['data']->Details ?? null;

            return response()->json([
                'status' => 'error',
                'message' => $message,
                'details' => $details,
            ]);
        }
    }    

}