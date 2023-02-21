<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiResetPasswordLinkRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages(): array
    {
        return [
            'email.required' => $this->localizeValidation('register.email_required'),
            'email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'password.required' => $this->localizeValidation('login.password_require'),
            'password.confirmed' => $this->localizeValidation('reset_password.password_confirmed'),
            'password.min' => $this->localizeValidation('reset_password.password_length'),
            'token.required' => $this->localizeValidation('reset_password.token_required'),
        ];
    }
}
