<?php

namespace App\Services\Tinkoff;

use Illuminate\Support\Facades\Log;
use App\Models\{Accumulation,
                Payment};
use App\Models\User;

class TinkoffE2C
{
    public TinkoffApi $api;
    private TinkoffApi $apiE2C;

    public function __construct()
    {
        $this->apiE2C   = new TinkoffApi(env('TINKOFF_TERMINAL_KEY_E2C'), env('TINKOFF_SECRET_KEY_E2C'), true);
        $this->api      = new TinkoffApi(env('TINKOFF_TERMINAL_KEY'), env('TINKOFF_SECRET_KEY'), true);
    }

    public function init($params)
    {
        $this->apiE2C->initA2C($params);
    }

    public function AddCustomer($CustomerKey)
    {
        $params = [
            'CustomerKey' => $CustomerKey,
        ];

        $this->apiE2C->AddCustomer($params);
    }

    public function GetCustomer($CustomerKey)
    {
        $this->apiE2C->GetCustomer(['CustomerKey' => $CustomerKey]);
    }

    public function RemoveCustomer($CustomerKey)
    {
        $this->apiE2C->RemoveCustomer(['CustomerKey' => $CustomerKey]);
    }

    public function Charge($PaymentId, $RebillId)
    {
        $params = [
            'PaymentId' => $PaymentId,
            'RebillId' => $RebillId,
        ];

        $this->api->charge($params);
    }
    public function Pay($PaymentId)
    {
        $params = [
            'PaymentId' => $PaymentId,
        ];

        $this->apiE2C->paymentA2C($params);
    }

    public function GetCardList($CustomerKey)
    {
        $this->apiE2C->getCardListA2C(['CustomerKey' => $CustomerKey]);
    }

    public function AddCard($CustomerKey, $checkType = '3DSHOLD')
    {
        $params = [
            'CustomerKey' => $CustomerKey,
            'CheckType' => $checkType,
        ];
        $this->apiE2C->addCardA2C($params);
    }

    public function RemoveCard($CustomerKey, $CardId)
    {
        $params = [
            'CustomerKey' => $CustomerKey,
            'CardId' => $CardId,
        ];
        $this->apiE2C->removeCardA2C($params);
    }

    /**
     * Метод создает новую копилку на стороне Тинькова
     */
    public function createSpDeal()
    {
        $params = [
            'Type' => "N1"
        ];
        $this->apiE2C->createSpDeal($params);
    }

    public function response()
    {
        $r = [];
        $resp = json_decode($this->apiE2C->response());

        $r['data']          = $resp;
        $r['status']        = isset($resp->Success)     && $resp->Success ? 'ok' : 'error';
        $r['customer_key']  = isset($resp->CustomerKey) && $resp->CustomerKey ? $resp->CustomerKey : null;
        $r['message']       = isset($resp->Message)     && $resp->Message ? $resp->Message : null;
        $r['details']       = isset($resp->Details)     && $resp->Details ? $resp->Details : null;
        $r['paymentUrl']    = isset($resp->PaymentURL)  && $resp->PaymentURL ? $resp->PaymentURL : null;
        
        return $r;
    }

    public function checkCustomer($customer)
    {
        $this->GetCustomer($customer);
        if($this->response()['status'] != 'ok'){
            $this->AddCustomer($customer);
        }
        return $this->response()['status'] == 'ok';
    }
    
    private function initPayout(Accumulation $accumulation, string $cardId, string $orderId)
    {
        $this->init([
            'Amount' => $accumulation->amount,
            'OrderId' => $orderId,
            'CardId' => $cardId,
            'DATA' => [
                'SpAccumulationId' => $accumulation->SpAccumulationId,
                'SpFinalPayout' => true
            ]
        ]);

        return $this->response();        
    }

    /**
     * Вывод из копилки $accumulation на карту $cardId (id Tinkoff) с номером $cardNumber и закрытие копилки
     */
    public function processPayout(Accumulation $accumulation, string $cardId, string $cardNumber)
    {
        Log::debug('Инициализация вывода');
        $user = $accumulation->user;
        $orderId = $user->id . date("_md_s_") . $accumulation->id;
        $resp = $this->initPayout($accumulation, $cardId, $orderId);

        if ($resp['data']->Success && $resp['data']->Success &&
             isset($resp['data']->Status) && $resp['data']->Status == 'CHECKED') {
            Log::debug('Payout status CHECKED');
            $paymentId = $resp['data']->PaymentId;
            $payOutAmount = (int)$resp['data']->Amount;

            $this->Pay($paymentId);
            $resp = $this->response();

            if (isset($resp['data']->Success) && $resp['data']->Success && 
                isset($resp['data']->Status) && $resp['data']->Status == 'COMPLETED') {
                Log::debug('Payout status COMPLETED');

                // Успешная выплата
                $accumulation->close();
                Payment::createPayout($accumulation, $user, $payOutAmount, $orderId, $resp['data']->PaymentId, $cardNumber);

                return [
                    'status' => 'ok',
                    'message' => 'Выплата на карту осуществлена'
                ];
            } else {
                // Неуспешная выплата
                Log::error('Payout error: ' . json_encode($resp));

                return [
                    'status' => 'error',
                    'message' => $resp['data']->Message ?? null,
                    'details' => $resp['data']->Details ?? null,
                ];
            }
        } else {
            Log::error('Payout error (status not checked): ' . json_encode($resp));

            return [
                'status' => 'error',
                'message' => $resp['data']->Message ?? null,
                'details' => $resp['data']->Details ?? null,
            ];
        }
    }

    /**
     * Возвращает список привязанных карт пользователя
     */
    public function getCardsList(User $user): array
    {
        $cardsList = [];
        $this->GetCardList($user->getCustomerKey());
        $cards = $this->response();
        if (isset($cards['data']) && is_array($cards['data'])) {
            foreach ($cards['data'] as $card){
                $cardsList[] = ['CardId' => $card->CardId ?? null,
                                'Pan' => $card->Pan ?? null,
                                'Status' => $card->Status ?? null 
                                ];
            }
        }

        return $cardsList;
    }

}