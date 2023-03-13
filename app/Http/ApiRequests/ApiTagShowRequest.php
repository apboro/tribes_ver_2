<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTagShowRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:tags'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('tag.id_required'),
            'id.integer' => $this->localizeValidation('tag.id_integer'),
            'id.exists' => $this->localizeValidation('tag.id_exists'),
        ];
    }
}
