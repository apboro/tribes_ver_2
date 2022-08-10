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
            'id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id' => 'user_id - обязательное поле',
        ];
    }
}
