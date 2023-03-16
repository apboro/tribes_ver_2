<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTelegramUserFilterRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'name'=>'string',
            'accession_date_from'=>'date_format:Y-m-d',
            'accession_date_to'=>'date_format:Y-m-d',
            'community_id'=>'integer|min:0'
        ];
    }


}
