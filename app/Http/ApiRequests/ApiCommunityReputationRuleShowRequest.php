<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiCommunityReputationRuleShowRequest extends ApiRequest
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
            'id' => 'required|integer|exists:community_reputation_rules,id'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('id_required'),
            'id.integer' => $this->localizeValidation('id_integer'),
            'id.exists' => $this->localizeValidation('id_exists'),
        ];
    }
}
