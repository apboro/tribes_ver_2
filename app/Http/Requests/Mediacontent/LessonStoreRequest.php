<?php

namespace App\Http\Requests\Mediacontent;

use Illuminate\Foundation\Http\FormRequest;

class LessonStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer',
            'course_id' => 'required',
//            'lesson_meta.title' => 'required',
            'modules.*.id' => 'required|integer',
            'modules.*.template_id' => 'required|integer',
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('id', (int)$this->id);
    }
}
