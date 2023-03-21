<?php

namespace App\Http\ApiRequests\Course;

use App\Helper\PseudoCrypt;
use App\Http\ApiRequests\ApiRequest;

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
