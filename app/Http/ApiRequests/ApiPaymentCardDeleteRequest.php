<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiPaymentCardDeleteRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'card_id'=>'required'
        ];
    }

    public function messages():array
    {
        return [
            'card_id.required'=>$this->localizeValidation('payment.card_id_required')
        ];
    }
}
