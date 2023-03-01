<?php


namespace App\Services;


class TinkoffE2C
{
    public TinkoffApi $api;
    private TinkoffApi $apiE2C;

    public function __construct()
    {
        $this->apiE2C   = new TinkoffApi(env('TINKOFF_TERMINAL_KEY_E2C'), env('TINKOFF_SECRET_KEY_E2C'));
        $this->api      = new TinkoffApi(env('TINKOFF_TERMINAL_KEY'), env('TINKOFF_SECRET_KEY'));
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
}