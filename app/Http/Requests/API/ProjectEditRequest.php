<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProjectEditRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|int',
            'title' => 'required|string'
        ];
    }
}