<?php

namespace App\Http\ApiRequests;

class ApiUserManagerExportRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'type'=>'string'
        ];
    }

    public function messages():array
    {
        return [
            'type.string' => $this->localizeValidation('export.type_string'),
        ];
    }
}
