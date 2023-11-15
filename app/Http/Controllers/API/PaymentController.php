<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PayOutRequest;
use App\Models\Accumulation;
use App\Services\Tinkoff\TinkoffE2C;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public TinkoffE2C $e2c;

    public function __construct()
    {
        $this->e2c = new TinkoffE2C();
    }

    public function payout(PayOutRequest $request)
    {
        $accumulation = Accumulation::where('SpAccumulationId', (int)$request['accumulationId'])->first(); //?? Auth::user()->getActiveAccumulation()
        if(!$accumulation){
            return response()->json([
                'status' => 'error',
                'message' => 'Такого накопления не найдено',
            ]);
        }
        $orderId = Auth::user()->id . date("_md_s");
        $params = [
            'Amount' => $accumulation->amount,
            'OrderId' => $orderId,
            'CardId' => $request['CardId'],
            'DATA' => [
                'SpAccumulationId' => $accumulation->SpAccumulationId,
                'SpFinalPayout' => true
            ]
        ];
        $this->e2c->init($params);

        $resp = $this->e2c->response();
        if(isset($resp['data']->Success) && $resp['data']->Success && isset($resp['data']->Status) && $resp['data']->Status == 'CHECKED'){
            $paymentId = $resp['data']->PaymentId;

            $payOutAmount = (int)$resp['data']->Amount;

            $this->e2c->Pay($paymentId);
            $resp = $this->e2c->response();

            if(isset($resp['data']->Success) && $resp['data']->Success && isset($resp['data']->Status) && $resp['data']->Status == 'COMPLETED'){
                // Успешная выплата

//                $accumulation->amount -= $payOutAmount;
                $accumulation->status = 'closed';
                $accumulation->save();

                $payment = new Payment();
                $payment->type = 'payout';
                $payment->amount = $payOutAmount;
                $payment->status = $resp['data']->Status;
                $payment->from = Auth::user()->name ?? 'Анонимный получаетль';
                $payment->community_id = Auth::user()->communities()->first()->id;
                $payment->add_balance = 0;
                $payment->OrderId = $orderId;
                $payment->paymentId = $resp['data']->PaymentId;
                $payment->save();

                return response()->json([
                    'status' => 'ok',
                    'message' => 'Выплата на карту осуществлена'
                ]);

            } else {
                // Неуспешная выплата

                $message = $resp['data']->Message;
                $details = $resp['data']->Details;

                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'details' => $details,
                ]);
            }
        } else {
            $message = $resp['data']->Message ?? null;
            $details = $resp['data']->Details ?? null;

            return response()->json([
                'status' => 'error',
                'message' => $message,
                'details' => $details,
            ]);
        }
    }

    private function checkCustomer($custumer)
    {
        $this->e2c->GetCustomer($custumer);
        if($this->e2c->response()['status'] != 'ok'){
            $this->e2c->AddCustomer($custumer);
        }

        return $this->e2c->response()['status'] == 'ok';
    }

    public function addCard()
    {
        $customer = env('TINKOFF_PREFIX') . '_user_' . Auth::user()->id;

        if(!$this->checkCustomer($customer)){
            return response()->json([
                'status' => 'error',
                'message' => 'Банк не может идентифицировать пользователя'
            ]);
        }

        $this->e2c->AddCard($customer);

        $resp = $this->e2c->response();
        unset($resp['data']);
        $resp['redirect'] = $resp['paymentUrl'];
        unset($resp['paymentUrl']);

        return response()->json($resp);
    }

    public function removeCard(Request $request)
    {
        $customer = env('TINKOFF_PREFIX') . '_user_' . Auth::user()->id;

        if(!$this->checkCustomer($customer)){
            return response()->json([
                'status' => 'error',
                'message' => 'Банк не может идентифицировать пользователя'
            ]);
        }

        $this->e2c->RemoveCard($customer, $request['CardId']);

        $resp = $this->e2c->response();

//        dd($resp);
        unset($resp['data']);
        $resp['redirect'] = $resp['paymentUrl'];
        unset($resp['paymentUrl']);

        return response()->json($resp);
    }

    public function cardList()
    {
        $customer = env('TINKOFF_PREFIX') . '_user_' . Auth::user()->id;

        if(!$this->checkCustomer($customer)){
            return response()->json([
                'status' => 'error',
                'message' => 'Банк не может идентифицировать пользователя'
            ]);
        }

        $this->e2c->GetCardList($customer);

        $resp = $this->e2c->response();

        $r = [];
        $r['cards'] = $resp['data'];
        $r['Statuses'] = [
            'A' => 'active',
            'I' => 'inactive',
            'E' => 'expired',
            'D' => 'deactivated',
        ];
        $r['CardTypes'] = [
            '0' => 'write-off',
            '1' => 'write-on',
            '2' => 'write-on-off',
        ];

        return response()->json($r);
    }
}
