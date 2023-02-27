<?php

namespace App\Http\ApiRequests;

use Askoldex\Teletant\Api;
use Illuminate\Foundation\Http\FormRequest;

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
