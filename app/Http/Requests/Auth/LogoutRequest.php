<?php

namespace App\Http\Requests\Auth;


use App\Http\ApiRequests\ApiRequest;


class LogoutRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

}
