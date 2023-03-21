<?php

namespace App\Http\ApiRequests\Course;

use App\Http\ApiRequests\ApiRequest;

class ApiCourseShowRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }


    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('course.id_required'),
            'id.integer' => $this->localizeValidation('course.id_integer'),
        ];
    }
}
