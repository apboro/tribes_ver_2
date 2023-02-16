<?php

namespace App\Http\Requests\Auth;

use App\Http\ApiRequests\ApiRequest;

class LoginAsRequest extends ApiRequest
{
    /*public function authorize()
    {
        return true;
    }*/

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'id' => 'user_id - обязательное поле',
        ];
    }
}
