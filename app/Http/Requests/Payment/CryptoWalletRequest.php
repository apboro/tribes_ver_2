<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CryptoWalletRequest extends FormRequest
{
    public function rules(): array
    {
        return ['order_id' => 'required|integer|min:1',
                'customer_telegram_user_id' => 'required|integer|min:1',
                'status' => 'required|string|min:1'];
    }
}