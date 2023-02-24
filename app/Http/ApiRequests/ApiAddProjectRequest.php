<?php

namespace App\Http\ApiRequests;

use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Foundation\Http\FormRequest;

class ApiAddProjectRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'title' => 'string',
            'communities' => 'array',
            "communities.*"  => ["int", new OwnCommunityRule()],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string'=>$this->localizeValidation('project.title')
        ];
    }
}
