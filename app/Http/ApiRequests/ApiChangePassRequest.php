<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

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
            'password.required' => trans('responses/validation.register.password_require'),
            'password.string' => trans('responses/validation.register.incorrect_format'),
            'password.min' => trans('responses/validation.register.password_min_length'),
            'password.confirmed' => trans('responses/validation.register.password_confirm'),
        ];
    }
}
