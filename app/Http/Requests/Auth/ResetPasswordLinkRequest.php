<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordLinkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
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
        ];
    }
}
