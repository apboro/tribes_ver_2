<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiResetPasswordLinkRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password'=>'required|confirmed|min:6',
            'token'=>'required'
        ];
    }

    public function prepareForValidation(): void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages(): array
    {
        return [
            'email.required'=>trans('responses/validation.register.email_required'),
            'email.email'=>trans('responses/validation.login.email_incorrect_format'),
            'password.required'=>trans('responses/validation.login.password_require'),
            'password.confirmed'=>trans('responses/validation.reset_password.password_confirmed'),
            'password.min'=>trans('responses/validation.reset_password.password_length'),
            'token.required'=>trans('responses/validation.reset_password.token_required'),
        ];
    }
}
