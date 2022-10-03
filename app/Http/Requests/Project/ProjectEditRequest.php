<?php

namespace App\Http\Requests\Project;

use App\Rules\OwnProjectRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectEditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required','integer',new OwnProjectRule()],
            'title' => 'required|string',
        ];
    }
}