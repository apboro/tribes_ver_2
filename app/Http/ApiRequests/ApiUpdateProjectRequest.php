<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiUpdateProjectRequest extends ApiRequest
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
            'title' => 'required|string'
        ];
    }


    public function messages(): array
    {
        return [
            'id.required'=>$this->localizeValidation('project.id_required'),
            'id.integer'=>$this->localizeValidation('project.id_integer'),
            'id.min'=>$this->localizeValidation('project.id_integer'),
            'title.string'=>$this->localizeValidation('project.string'),
            'title.required' => $this->localizeValidation('project.title_required')
        ];
    }
}
