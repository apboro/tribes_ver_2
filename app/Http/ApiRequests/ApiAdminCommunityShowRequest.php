<?php

namespace App\Http\ApiRequests;

class ApiAdminCommunityShowRequest extends ApiRequest
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
            'id'=>'required|integer|min:1|exists:communities'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('community.id_required'),
            'id.integer' => $this->localizeValidation('community.id_integer'),
            'id.exists' => $this->localizeValidation('community.not_found'),
        ];
    }
}
