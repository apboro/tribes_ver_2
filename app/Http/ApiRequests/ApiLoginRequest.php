<?php

namespace App\Http\ApiRequests;

class ApiLoginRequest extends ApiRequest
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
            'email.required' => $this->localizeValidation('login.email_required'),
            'email.email'=>$this->localizeValidation('login.email_incorrect_format'),
            'password.required' => $this->localizeValidation('login.password_require'),
        ];
    }
}
