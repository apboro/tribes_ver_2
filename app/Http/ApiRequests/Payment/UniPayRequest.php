<?php

namespace App\Http\ApiRequests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitpayRequest extends FormRequest
{
    private function buildSignature(array $data): string
    {
        unset($data['params']['signature']);
        ksort($data['params']);
        $secretKey = (config('unitpay.test') === true) ? config('unitpay.secretKeyTest') : config('unitpay.secretKey');
        $buildedSignature = $data['method'] . '{up}' . 
                            implode('{up}', $data['params']) .
                            '{up}' . $secretKey;
                            
        return hash('sha256', $buildedSignature);
    }

    public function authorize(): bool
    {
        $data = parent::all();
        if (!isset($data['method']) || !isset($data['params']['signature'])) {
            return false;
        }
 
        if ($this->buildSignature($data) !== $data['params']['signature']) {
            return false;
        }

        return config('unitpay.checkIP') ? array_search($this->ip(), config('unitpay.ips')) : true;
    }

    public function rules(): array
    {
        return [];
    }
}
