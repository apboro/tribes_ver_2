<?php

namespace App\Services\Tinkoff;

use App\Models\Payment as P;
use App\Models\User\UserLegalInfo;
use Illuminate\Support\Facades\Log;

class Bill extends Acquiring
{
    public P $payment;
    protected $params;
    protected $legal;
    protected const SECONDS_AT_DAY = 86400;

    protected const API_URL = 'https://business.tinkoff.ru/openapi/api/v1/invoice/send';
    //protected const API_URL = 'https://business.tinkoff.ru/openapi/sandbox/api/v1/invoice/send';

    protected const API_URL_GET_STATUS = 'https://business.tinkoff.ru/openapi/api/v1/openapi/invoice/{invoiceId}/info';
    //protected const API_URL_GET_STATUS = 'https://business.tinkoff.ru/openapi/sandbox/api/v1/openapi/invoice/{invoiceId}/info';
    
    public function setLegal(UserLegalInfo $legal)
    {
        $this->legal = $legal;

        return $this;
    }

    public function getDate(int $addDays = 0)
    {
        return date('Y-m-d', time() + self::SECONDS_AT_DAY * $addDays);
    }

    public function getExpireDate()
    {
        return $this->getDate(config('tinkoff.billDaysActive'));
    }

    public function geteDatesFoCheckStatus()
    {
        return $this->getDate( - config('tinkoff.billDaysActive') - 3);
    }

    private function getLawerInformation()
    {
        $info['payer']['name'] = $this->legal['name'] ?? '';
        $info['payer']['inn'] = $this->legal['inn'] ?? '';
        if ($this->legal['kpp']) {
            $info['payer']['kpp'] = $this->legal['kpp'];
        }
        $info['email'] = $this->legal['email'] ?? '';
        $info['phone'] = $this->legal['phone'] ?? '';

        return $info;
    }

    private function getProductInformation()
    {
        return [[
            'name' => $this->serviceName,
            'price' => $this->payment->priceInRubles,
            'unit' => 'Шт',
            'vat' => 'None',
            'amount' => $this->quantity,
        ]];
    }

    public function setPayment($payment): self
    {
        $this->payment = $payment;
        if ($payment->amount) {
            $this->setAmount($payment->amount);
        }
        if ($payment->type) {
            $this->setType($payment->type);
        }

        return $this;
    }

    private function buildParams(): array
    {
        $lawerInfo = $this->getLawerInformation();

        return [
            'invoiceNumber' => $this->orderId,
            'dueDate' => $this->getExpireDate(),
            'invoiceDate' => $this->getDate(),
            //'accountNumber' => '',
            'payer' => $lawerInfo['payer'],
            'items' => $this->getProductInformation(),
            'contacts' => [[
                'email' => $lawerInfo['email'],
            ]],
            //'contactPhone' => $lawerInfo['phone'],
            'comment' => $this->serviceName,
        ];
    }

    static public function sendRequest($url, $method, $fileds = [])
    {
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . config('tinkoff.billBearerToken')
            ),
        ];

        if (!empty($fileds)) {
            $options[CURLOPT_POSTFIELDS] = \json_encode($fileds);
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            return new \stdClass();
        }

        return \json_decode($response);
    }

    public function run()
    {
        $response = self::sendRequest(self::API_URL, 'POST', $this->buildParams());

        if (isset($response->pdfUrl) && $response->pdfUrl) {
            $this->payment->updateRecord([
                'OrderId' => $this->orderId,
                'paymentUrl' => $response->pdfUrl,
                'bill_id' => $response->invoiceId ?? '',
                'status' => 'SUBMITTED',
                'token' => hash('sha256', $this->payment->id),
            ]);

            return $this->payment;
        } else {
            Log::alert('Ошибка при выставлении счета', ['response' => $response, 'object' => $this]);

            return false;
        }
    }

    public function getStatus($invoiceId): string
    {
        $apiURL = str_replace('{invoiceId}', $invoiceId, self::API_URL_GET_STATUS);
        $response = Bill::sendRequest($apiURL, 'GET');

        if (isset($response->status) && $response->status) {
            return $response->status;
        } else {
            Log::alert('Ошибка при проверке статуса счета', ['response' => $response, 'object' => $this]);

            return '';
        }
    }
}