<?php

namespace App\Http\Requests\API;

use App\Rules\Knowledge\OwnCommunityRule;
use App\Rules\OwnProjectRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectAttachRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => ['required','integer',new OwnProjectRule()],
            'communities' => 'array',
            'communities.*' => ['integer',new OwnCommunityRule()],
        ];
    }
}