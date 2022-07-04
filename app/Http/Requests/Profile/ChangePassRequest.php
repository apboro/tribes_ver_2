<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Пароль обязателен для заполнения',
            'password.string' => 'Неверный формат',
            'password.min' => 'Минимальная длинна - 8 символов',
            'password.confirmed' => 'Пароль должен быть подтвержден',
        ];
    }
}
