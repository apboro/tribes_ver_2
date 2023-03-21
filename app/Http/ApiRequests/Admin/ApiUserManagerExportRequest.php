<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;

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
