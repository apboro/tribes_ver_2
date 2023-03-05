<?php

namespace App\Http\ApiRequests;

use App\Helper\PseudoCrypt;

class ApiCourseShowForAllRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'id' => 'required',
        ];
    }

    public function prepareForValidation():void
    {
        $this->merge(['id'=>(int) PseudoCrypt::unhash($this->route('hash'))]);

    }

    public function messages(): array
    {
        return [
            'hash.required'=> $this->localizeValidation('course.hash_required')
        ];
    }

}
