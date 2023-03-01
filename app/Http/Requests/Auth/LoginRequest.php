<?php

namespace App\Http\Requests\Auth;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Attributes as OAT;

class LoginRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages(): array
    {
        return [
            'email.required' => 'email - обязательное поле',
            'password.required' => 'Пароль - обязательное поле',
        ];
    }
}
