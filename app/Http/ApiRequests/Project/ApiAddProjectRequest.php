<?php

namespace App\Http\ApiRequests\Project;

use App\Http\ApiRequests\ApiRequest;
use App\Rules\Knowledge\OwnCommunityRule;

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
