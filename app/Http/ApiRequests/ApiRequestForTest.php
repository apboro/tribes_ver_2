<?php

namespace App\Http\ApiRequests;

use Illuminate\Support\Facades\Auth;

class ApiRequestForTest extends ApiRequest
{
    public function authorize(): bool
    {
        if(!Auth::user()){
            $this->failedAuthorization();
        }
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'test' => mb_strtolower($this->test),
        ]);
    }

}