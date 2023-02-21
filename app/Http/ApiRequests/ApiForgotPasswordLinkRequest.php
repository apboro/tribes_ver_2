<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiForgotPasswordLinkRequest extends ApiRequest
{
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
            'email.required' => $this->localizeValidation('register.email_required'),
            'email.email'=>$this->localizeValidation('login.email_incorrect_format'),
        ];
    }
}
