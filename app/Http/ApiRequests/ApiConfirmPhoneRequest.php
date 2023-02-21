<?php

namespace App\Http\ApiRequests;

class ApiConfirmPhoneRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|integer',
            'code' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => $this->localizeValidation('phone.required'),
            'phone.integer' => $this->localizeValidation('phone.incorrect_format'),
            'code.required' => $this->localizeValidation('phone.code_required'),
            'code.integer' => $this->localizeValidation('phone.code_incorrect_format'),
        ];
    }
}
