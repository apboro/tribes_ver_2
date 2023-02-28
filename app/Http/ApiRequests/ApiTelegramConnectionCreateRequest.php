<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTelegramConnectionCreateRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        return [
            'platform'=>'required|string',
            'type'=>'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'platform.required'=>$this->localizeValidation('platform.required'),
            'platform.string'=>$this->localizeValidation('platform.string'),
            'type.required'=>$this->localizeValidation('type.required'),
            'type.string'=>$this->localizeValidation('type.string')
        ];
    }
}
