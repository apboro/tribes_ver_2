<?php

namespace App\Http\Requests\Mediacontent;

use Illuminate\Foundation\Http\FormRequest;

class CoursePayRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле email обязательно для заполнения.',
            'email.email' => 'Значение поля email должно быть действительным электронным адресом.',
        ];
    }
}
