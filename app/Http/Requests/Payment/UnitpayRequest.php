<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
        Log::info('X-Forwarded-For', ['X-Forwarded-For' => $this->header('X-Forwarded-For'), 'IP' => $this->ip()]);
        $data = parent::all();
        if (!isset($data['method']) || !isset($data['params']['signature'])) {
            Log::info('No Unitpay signature');
            return false;
        }
 
        if ($this->buildSignature($data) !== $data['params']['signature']) {
            Log::info('Bad Unitpay signature');
            return false;
        }
        Log::info('Unitpay signature is right');

        return config('unitpay.checkIP') ? array_search($this->header('X-Real-IP'), config('unitpay.ips')) : true;
    }

    public function rules(): array
    {
        return [];
    }
}
