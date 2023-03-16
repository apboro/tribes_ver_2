<?php

namespace App\Http\ApiRequests;


class ApiCommunityFilterRequest extends ApiRequest

{


    public function rules():array
    {
        return [
            'tag_name'=>'string',
            'date_from'=>'date_format:Y-m-d',
            'date_to'=>'date_format:Y-m-d'
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.date_format'=>$this->localizeValidation('date.incorrect_format'),
            'date_to.date_format'=>$this->localizeValidation('date.incorrect_format')
        ];
    }
}
