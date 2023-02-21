<?php

namespace App\Http\ApiRequests;

class ApiConfirmCodeRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'sms_code'  => 'required|integer'
        ];
    }

    public function messages():array
    {
        return [
            'sms_code.required' => $this->localizeValidation('phone.sms_code_required'),
            'sms_code.integer' => $this->localizeValidation('phone.sms_code_not_valid'),
        ];
    }
}
