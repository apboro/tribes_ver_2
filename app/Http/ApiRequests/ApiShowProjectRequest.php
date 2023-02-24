<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class ApiShowProjectRequest extends ApiRequest
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
            'id' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('project.id_required'),
            'id.integer' => $this->localizeValidation('project.id_integer'),
            'id.min' => $this->localizeValidation('project.id_integer'),
        ];
    }
}
