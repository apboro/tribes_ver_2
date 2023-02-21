<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

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
            'sms_code.required' => trans('responses/validation.phone.sms_code_required'),
            'sms_code.integer' => trans('responses/validation.phone.sms_code_not_valid'),
        ];
    }
}
