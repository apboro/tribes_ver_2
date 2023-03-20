<?php

namespace App\Http\ApiRequests;

class ApiUserManagerBlockRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules():array
    {
        return [
            'id'=>'required|integer|min:1'
        ];
    }

    public function messages():array
    {
        return [
            'id.required' => $this->localizeValidation('manager.user_id_required'),
            'id.integer' => $this->localizeValidation('manager.user_id_integer'),
        ];
    }
}
