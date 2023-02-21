<?php

namespace App\Http\ApiRequests;

use Askoldex\Teletant\Api;
use Illuminate\Foundation\Http\FormRequest;

class ApiConfirmPhoneRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'phone' => 'required|integer',
            'code'  => 'required|integer'
        ];
    }

    public function messages():array
    {
        return [
            'phone.required' => trans('responses/validation.phone.required'),
            'phone.integer' => trans('responses/validation.phone.incorrect_format'),

            'code.required' => trans('responses/validation.phone.code_required'),
            'code.integer' => trans('responses/validation.phone.code_incorrect_format'),
            // 'phone.unique' => 'Номер телефона занят',
        ];
    }
}
