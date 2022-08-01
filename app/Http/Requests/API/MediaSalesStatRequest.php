<?php

namespace App\Http\Requests\API;

use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Foundation\Http\FormRequest;

/** @property int $community_id */
class MediaSalesStatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'community_id' => ['required','integer',new OwnCommunityRule()],
        ];
    }
}