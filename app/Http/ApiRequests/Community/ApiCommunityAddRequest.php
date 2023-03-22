<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;

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
