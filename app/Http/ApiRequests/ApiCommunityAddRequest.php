<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiCommunityAddRequest extends ApiRequest
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
