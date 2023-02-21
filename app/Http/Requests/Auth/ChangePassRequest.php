<?php

namespace App\Http\Requests\Auth;

use App\Http\ApiRequests\ApiRequest;

class ChangePassRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Пароль обязателен для заполнения',
            'password.string' => 'Неверный формат',
            'password.min' => 'Минимальная длина - 8 символов',
            'password.confirmed' => 'Пароль должен быть подтвержден',
        ];
    }
}
