<?php

namespace App\Http\ApiRequests;

class ApiChangePassRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => $this->localizeValidation('register.password_require'),
            'password.string' => $this->localizeValidation('register.incorrect_format'),
            'password.min' => $this->localizeValidation('register.password_min_length'),
            'password.confirmed' => $this->localizeValidation('register.password_confirm'),
        ];
    }
}
