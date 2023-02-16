<?php

namespace App\Http\Requests\Auth;

use App\Http\ApiRequests\ApiRequest;

class ResetPasswordLinkRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
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
        ];
    }
}
