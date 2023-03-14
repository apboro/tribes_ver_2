<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiDetachTagFromCommunityRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'tag_id'=>'required|integer|exists:tags,id|exists:community_tag,tag_id',
            'community_id'=>'required|integer|exists:communities,id|exists:community_tag,community_id'
        ];
    }

    public function messages(): array
    {
        return [
            'tag_id.required' => $this->localizeValidation('tag.id_required'),
            'tag_id.integer' => $this->localizeValidation('tag.id_integer'),
            'tag_id.exists' => $this->localizeValidation('tag.id_exists'),
            'community_id.required'=>$this->localizeValidation('community.id_required'),
            'community_id.integer'=>$this->localizeValidation('community.id_integer'),
            'community_id.exists'=>$this->localizeValidation('community.id_exists')

        ];
    }
}
