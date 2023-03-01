<?php

namespace App\Http\ApiRequests;

class ApiTelegramConnectionSearchRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'hash'=>'required'
        ];
    }

    public function messages(): array
    {
        return [
            'hash.required'=>$this->localizeValidation('hash_required')
        ];
    }
}
