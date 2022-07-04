<?php

namespace App\Http\Requests\Sms;

use Illuminate\Foundation\Http\FormRequest;

class SmsRepeatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hash' => ['required', 'string', 'exists:users,hash'],
        ];
    }

    public function prepareForValidation()
    {
    }

    public function messages()
    {
        return [
            'hash.required' => 'Хэш обязателен',
            'hash.string' => 'Хэш должен быть строкой',
        ];
    }
}
