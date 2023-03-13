<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTagStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => $this->localizeValidation('tag.name_required'),
            'name.min' => $this->localizeValidation('tag.name_min'),
            'name.max' => $this->localizeValidation('tag.name_min'),
        ];
    }
}
