<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginAsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages()
    {
        return [
            'email.required' => 'email - обязательное поле',
            'password.required' => 'Пароль - обязательное поле',
        ];
    }
}
