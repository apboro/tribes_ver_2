<?php

namespace App\Http\ApiRequests;

class ApiProjectShowRequest extends ApiRequest
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
            'id' => 'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('project.id_required'),
            'id.integer' => $this->localizeValidation('project.id_integer'),
        ];
    }
}
