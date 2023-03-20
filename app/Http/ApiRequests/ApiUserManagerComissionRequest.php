<?php

namespace App\Http\ApiRequests;

class ApiUserManagerComissionRequest extends ApiRequest
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
            'id'=>'required|integer|min:1',
            'commission'=>'required|numeric'
        ];
    }

    public function messages():array
    {
        return [
            'id.required' => $this->localizeValidation('manager.user_id_required'),
            'id.integer' => $this->localizeValidation('manager.user_id_integer'),
            'commission.required'=>$this->localizeValidation('commission.required'),
            'commission.numeric'=>$this->localizeValidation('commission.numeric'),
        ];
    }
}
