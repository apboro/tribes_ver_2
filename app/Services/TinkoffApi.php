<?php

namespace App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TinkoffApi
{
    private $api_url;
    private $api_e2c_url;
    private $terminalKey;
    private $secretKey;
    private $paymentId;
    private $status;
    private $error;
    private $response;
    private $paymentUrl;


    public static $taxations = [
        'osn'                => 'osn',                      // Общая СН
        'usn_income'         => 'usn_income',               // Упрощенная СН (доходы)
        'usn_income_outcome' => 'usn_income_outcome',       // Упрощенная СН (доходы минус расходы)
        'envd'               => 'envd',                     // Единый налог на вмененный доход
        'esn'                => 'esn',                      // Единый сельскохозяйственный налог
        'patent'             => 'patent'                    // Патентная СН
    ];

    public static $paymentMethod = [
        'full_prepayment' => 'full_prepayment',             //Предоплата 100%
        'prepayment'      => 'prepayment',                  //Предоплата
        'advance'         => 'advance',                     //Аванc
        'full_payment'    => 'full_payment',                //Полный расчет
        'partial_payment' => 'partial_payment',             //Частичный расчет и кредит
        'credit'          => 'credit',                      //Передача в кредит
        'credit_payment'  => 'credit_payment',              //Оплата кредита
    ];

    public static $paymentObject = [
        'commodity'             => 'commodity',             //Товар
        'excise'                => 'excise',                //Подакцизный товар
        'job'                   => 'job',                   //Работа
        'service'               => 'service',               //Услуга
        'gambling_bet'          => 'gambling_bet',          //Ставка азартной игры
        'gambling_prize'        => 'gambling_prize',        //Выигрыш азартной игры
        'lottery'               => 'lottery',               //Лотерейный билет
        'lottery_prize'         => 'lottery_prize',         //Выигрыш лотереи
        'intellectual_activity' => 'intellectual_activity', //Предоставление результатов интеллектуальной деятельности
        'payment'               => 'payment',               //Платеж
        'agent_commission'      => 'agent_commission',      //Агентское вознаграждение
        'composite'             => 'composite',             //Составной предмет расчета
        'another'               => 'another',               //Иной предмет расчета
    ];

    public static $vats = [
        'none'  => 'none',                                  // Без НДС
        'vat0'  => 'vat0',                                  // НДС 0%
        'vat10' => 'vat10',                                 // НДС 10%
        'vat20' => 'vat20'                                  // НДС 20%
    ];

    public function __construct($terminalKey, $secretKey)
    {
//        throw new \Exception('DEPRECATED CLASS');
        $this->api_url = 'https://securepay.tinkoff.ru/v2/';
        $this->api_e2c_url = 'https://securepay.tinkoff.ru/e2c/v2/';
//        $this->api_e2c_url = 'https://rest-api-test.tinkoff.ru/e2c/';
        $this->terminalKey = $terminalKey;
        $this->secretKey = $secretKey;
    }

    public function __get($name)
    {
        switch ($name) {
            case 'paymentId':
                return $this->paymentId;
            case 'status':
                return $this->status;
            case 'error':
                return $this->error;
            case 'paymentUrl':
                return $this->paymentUrl;
            case 'response':
                return htmlentities($this->response);
            default:
                if ($this->response) {
                    if ($json = json_decode($this->response, true)) {
                        foreach ($json as $key => $value) {
                            if (strtolower($name) == strtolower($key)) {
                                return $json[$key];
                            }
                        }
                    }
                }

                return false;
        }
    }


    public function init($args)
    {
        return $this->buildQuery('Init', $args);
    }

    public function initA2C($args)
    {
        return $this->buildQuery('Init', $args, true);
    }

    public function getState($args)
    {
        return $this->buildQuery('GetState', $args);
    }

    public function confirm($args)
    {
        return $this->buildQuery('Confirm', $args);
    }

    public function charge($args)
    {
        return $this->buildQuery('Charge', $args);
    }

    public function addCustomer($args)
    {
        return $this->buildQuery('AddCustomer', $args);
    }

    public function getCustomer($args)
    {
        return $this->buildQuery('GetCustomer', $args);
    }

    public function removeCustomer($args)
    {
        return $this->buildQuery('RemoveCustomer', $args);
    }

    public function getCardList($args)
    {
        return $this->buildQuery('GetCardList', $args);
    }

    public function removeCard($args)
    {
        return $this->buildQuery('RemoveCard', $args);
    }

    public function removeCardA2C($args)
    {
        return $this->buildQuery('RemoveCard', $args, true);
    }

    public function addCardA2C($args)
    {
        return $this->buildQuery('AddCard', $args, true);
    }

    public function addCustomerA2C($args)
    {
        return $this->buildQuery('AddCustomer', $args, true);
    }

    public function getCardListA2C($args)
    {
        return $this->buildQuery('GetCardList', $args, true);
    }

    public function paymentA2C($args)
    {
        return $this->buildQuery('Payment', $args, true);
    }

    public function buildQuery($path, $args, $a2c = false)
    {
        $url = $a2c ? $this->api_e2c_url : $this->api_url;

        if(is_array($args)){
            if (!$a2c) {
                if (!array_key_exists('TerminalKey', $args)) {
                    $args['TerminalKey'] = $this->terminalKey;
                }
                if (!array_key_exists('Token', $args)) {
                    $args['Token'] = $this->_genToken($args);
                }
            } else {

                if (!array_key_exists('TerminalKey', $args)) {
                    $args['TerminalKey'] = $this->terminalKey;
                }

                $args = $this->updateSecuresData($args);

                if (!array_key_exists('X509SerialNumber', $args)) {

                    $args['X509SerialNumber'] = env('TINKOFF_X509SERIAL');
                }
            }
        }

        $url = $this->_combineUrl($url, $path);
        return $this->_sendRequest($url, $args);
    }

    private function _genSignatureValue($args)
    {
        return base64_encode(hash('gost-crypto', 1));
    }

    private function updateSecuresData($args)
    {
        $token = '';
        ksort($args);
        foreach ($args as $arg) {
            if (!is_array($arg)) {
                $token .= $arg;
            }
        }

        $hash = hash('sha256', $token);

        $binary = hex2bin($hash);

        $args['DigestValue'] = base64_encode( $binary );

        $certFile = Storage::disk('local')->get('private.key');

        $privateKey = openssl_pkey_get_private($certFile);

        openssl_sign($binary, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $args['SignatureValue'] =  base64_encode($signature);

        return $args;
    }

    private function _genToken($args)
    {
        $token = '';
        $args['Password'] = $this->secretKey;
        ksort($args);

        foreach ($args as $arg) {
            if (!is_array($arg)) {
                $token .= $arg;
            }
        }
        $token = hash('sha256', $token);

        return $token;
    }

    private function _combineUrl()
    {
        $args = func_get_args();
        $url = '';
        foreach ($args as $arg) {
            if (is_string($arg)) {
                if ($arg[strlen($arg) - 1] !== '/') $arg .= '/';
                $url .= $arg;
            } else {
                continue;
            }
        }

        return $url;
    }

    private function _sendRequest($api_url, $args)
    {
        $this->error = '';
        if (is_array($args)) {
            $args = json_encode($args);
        }

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));

//            TelegramBotService::sendMessage(-612889716, $api_url );
//            TelegramBotService::sendMessage(-612889716, $args );
//
//            Storage::prepend('Tinkoff_notify.log', 'req');
//            Storage::prepend('Tinkoff_notify.log', json_encode($curl));

            $out = curl_exec($curl);

            $this->response = $out;
            $json = json_decode($out);

            if ($json) {
                if (@$json->ErrorCode !== "0") {
                    $this->error = @$json->Details;
                } else {
                    $this->paymentUrl = @$json->PaymentURL;
                    $this->paymentId = @$json->PaymentId;
                    $this->status = @$json->Status;
                }
            }

            curl_close($curl);

            return $out;

        } else {
            throw new HttpException('Can not create connection to ' . $api_url . ' with args ' . $args, 404);
        }
    }

    public function response()
    {
        return $this->response;
    }

    public static function balanceAmount($isShipping, $items, $amount)
    {
        $itemsWithoutShipping = $items;

        if ($isShipping) {
            $shipping = array_pop($itemsWithoutShipping);
        }

        $sum = 0;

        foreach ($itemsWithoutShipping as $item) {
            $sum += $item['Amount'];
        }

        if (isset($shipping)) {
            $sum += $shipping['Amount'];
        }

        if ($sum != $amount) {
            $sumAmountNew = 0;
            $difference = $amount - $sum;
            $amountNews = [];

            foreach ($itemsWithoutShipping as $key => $item) {
                $itemsAmountNew = $item['Amount'] + floor($difference * $item['Amount'] / $sum);
                $amountNews[$key] = $itemsAmountNew;
                $sumAmountNew += $itemsAmountNew;
            }

            if (isset($shipping)) {
                $sumAmountNew += $shipping['Amount'];
            }

            if ($sumAmountNew != $amount) {
                $max_key = array_keys($amountNews, max($amountNews))[0];    // ключ макс значения
                $amountNews[$max_key] = max($amountNews) + ($amount - $sumAmountNew);
            }

            foreach ($amountNews as $key => $item) {
                $items[$key]['Amount'] = $amountNews[$key];
            }
        }

        return $items;
    }
}