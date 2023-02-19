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
            'email.required' => trans('responses/validation.register.email_required'),
            'email.email'=>trans('responses/validation.login.email_incorrect_format'),
        ];
    }
}
