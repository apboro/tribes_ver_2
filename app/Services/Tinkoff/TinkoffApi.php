<?php

namespace App\Services\Tinkoff;


use App\Services\TelegramLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        'osn' => 'osn',                      // Общая СН
        'usn_income' => 'usn_income',               // Упрощенная СН (доходы)
        'usn_income_outcome' => 'usn_income_outcome',       // Упрощенная СН (доходы минус расходы)
        'envd' => 'envd',                     // Единый налог на вмененный доход
        'esn' => 'esn',                      // Единый сельскохозяйственный налог
        'patent' => 'patent'                    // Патентная СН
    ];

    public static $paymentMethod = [
        'full_prepayment' => 'full_prepayment',             //Предоплата 100%
        'prepayment' => 'prepayment',                  //Предоплата
        'advance' => 'advance',                     //Аванc
        'full_payment' => 'full_payment',                //Полный расчет
        'partial_payment' => 'partial_payment',             //Частичный расчет и кредит
        'credit' => 'credit',                      //Передача в кредит
        'credit_payment' => 'credit_payment',              //Оплата кредита
    ];

    public static $paymentObject = [
        'commodity' => 'commodity',             //Товар
        'excise' => 'excise',                //Подакцизный товар
        'job' => 'job',                   //Работа
        'service' => 'service',               //Услуга
        'gambling_bet' => 'gambling_bet',          //Ставка азартной игры
        'gambling_prize' => 'gambling_prize',        //Выигрыш азартной игры
        'lottery' => 'lottery',               //Лотерейный билет
        'lottery_prize' => 'lottery_prize',         //Выигрыш лотереи
        'intellectual_activity' => 'intellectual_activity', //Предоставление результатов интеллектуальной деятельности
        'payment' => 'payment',               //Платеж
        'agent_commission' => 'agent_commission',      //Агентское вознаграждение
        'composite' => 'composite',             //Составной предмет расчета
        'another' => 'another',               //Иной предмет расчета
    ];

    public static $vats = [
        'none' => 'none',                                  // Без НДС
        'vat0' => 'vat0',                                  // НДС 0%
        'vat10' => 'vat10',                                 // НДС 10%
        'vat20' => 'vat20'                                  // НДС 20%
    ];

    public function __construct($terminalKey, $secretKey)
    {
        if (config('tinkoff.test') && !Str::contains($terminalKey, 'DEMO')) {
            $this->api_url = config('tinkoff.urls.test_url');
            $this->api_e2c_url = config('tinkoff.urls.test_e2c_url');
            Log::info('Tinkoff use test urls');
        } else {
            $this->api_url = config('tinkoff.urls.real_url');
            $this->api_e2c_url = config('tinkoff.urls.real_e2c_url');
            Log::info('Tinkoff use real urls');
        }
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

    public function reSend()
    {
        return $this->buildQuery('Resend', '');
    }

    public function checkOrder($args)
    {
        return $this->buildQuery('CheckOrder', $args);
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

    public function cancel($args)
    {
        return $this->buildQuery('Cancel', $args);
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

    public function createSpDeal($args)
    {
        return $this->buildQuery('createSpDeal', $args);
    }

    public function paymentA2C($args)
    {
        return $this->buildQuery('Payment', $args, true);
    }

    public function buildQuery($path, $args, $a2c = false)
    {
        $url = $a2c ? $this->api_e2c_url : $this->api_url;

        if (is_array($args)) {
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

                if (!array_key_exists('Token', $args)) {
                    $token =  $this->_genToken($args);
                    $args['Token'] = $this->_genToken($args);
                    log::info('set Token: '. json_encode($args, JSON_UNESCAPED_UNICODE));
                }

                log::info('allowed Token: '. json_encode($args['Token'], JSON_UNESCAPED_UNICODE));

                $args = $this->updateSecuresData($args);

                if (!array_key_exists('X509SerialNumber', $args)) {

                    $args['X509SerialNumber'] = env('TINKOFF_X509SERIAL');
                }
            }
        }
        if (env('DEBUG_TINKOFF', false)) {
            dump("url: " . $url . " аргументы:" . json_encode($args, JSON_UNESCAPED_UNICODE));
            TelegramLogService::staticSendLogMessage("url: " . $url . " аргументы:" . json_encode($args, JSON_UNESCAPED_UNICODE));
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

        $args['DigestValue'] = base64_encode($binary);

        $certFile = Storage::disk('local')->get('private.key');

        $privateKey = openssl_pkey_get_private($certFile);

        openssl_sign($binary, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        $args['SignatureValue'] = base64_encode($signature);

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
        Log::info('I am in _sendRequest', [$api_url, $args]);
        $this->error = '';
        if (is_array($args)) {
            $args = json_encode($args);
        }

        Log::debug('YES: PAY_TEST : ' . env('PAY_TEST') . ', PAY_TEST(false) ' . env('PAY_TEST', false));
        if (env('PAY_TEST') === 'yes') {
            $testArgs = json_decode($args, true);
            Log::debug('Tinkoff api send fake request', [
                'api_url' => $api_url,
                'args' => $testArgs
            ]);

            // $path = Str::afterLast(rtrim($api_url, '/'), '/');
            $path = Str::afterLast($api_url, 'tinkoff.ru/');
       
            // создание хеша для тестового файла данных по платежу можно опираться только на путь и сумму платежа
            // потому что все остальные параметры в $args являются динамическими,
            // потому автотесты платежей разделять по Amount, каждый тест должен иметь свою сумму
            // Но есть проблема: во время запроса на выплату "Payment" суммы нет, т.к. она передается в "init"...
            $amount = $testArgs['Amount'] ?? 0;

            // Называем файл методом Тинька. Сумму передаем любую, в ответе заменяем на переданную.
            //$file_name = md5($path . $amount);
            $file_name = str_replace('/', '-', substr($path, 0, -1));
            $storage = Storage::disk('test_data');
            Log::info('FAKE RESPONSE FILE NAME: ' . $file_name);

            $this->response = $storage->exists("payment/$file_name.json") ?
                $storage->get("payment/$file_name.json") :
                $storage->get("payment/file.json");
            
            $this->response = str_replace('{Amount}', $amount ?? '', $this->response);
            $this->response = str_replace('{OrderId}', $testArgs['OrderId'] ?? '', $this->response);
            $this->response = str_replace('{CardId}', $testArgs['CardId'] ?? '', $this->response);
            $this->response = str_replace('{PaymentId}', rand(1000000,9999999), $this->response);

            return $this->response;
        } else if ($curl = curl_init()) {

            $logArgs = json_decode($args, true);
            Log::debug('Tinkoff api send real request', [
                'api_url' => $api_url,
                'args' => $logArgs
            ]);

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
            Log::info($this->response);
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