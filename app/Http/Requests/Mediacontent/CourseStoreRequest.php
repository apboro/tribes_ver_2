<?php

namespace App\Http\Requests\Mediacontent;

use Illuminate\Foundation\Http\FormRequest;

class CourseStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'course_meta.title' => 'required',
//            'lesson_meta.active' => 'required',
//            'lesson_meta.price' => 'required',

//            'id' => 'required|integer',
//            'lesson_meta.title' => 'required',
//            'modules.*.id' => 'required|integer',
//            'modules.*.template_id' => 'required|integer',
        ];
    }

    function prepareForValidation()
    {
        $this->headers->set('Accept', 'application/json');
    }
}
