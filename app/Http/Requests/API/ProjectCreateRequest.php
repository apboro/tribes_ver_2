<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string'
        ];
    }
}