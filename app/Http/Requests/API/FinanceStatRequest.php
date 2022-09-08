<?php

namespace App\Http\Requests\API;

use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Foundation\Http\FormRequest;

class FinanceStatRequest extends FormRequest
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
