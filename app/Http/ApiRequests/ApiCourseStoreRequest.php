<?php

namespace App\Http\ApiRequests;
class ApiCourseStoreRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'id' => 'required',
            'course_meta.title' => 'required',
        ];
    }

    public function messages(): array
    {
        return [

        ];
    }

    public function prepareForValidation():void
    {
        $this->headers->set('Accept', 'application/json');
    }
}
