<?php

namespace App\Http\Requests\API;

use App\Rules\OwnCommunityGroupRule;
use Illuminate\Foundation\Http\FormRequest;

class TeleDialogStatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'community_ids' => ['required','string',new OwnCommunityGroupRule()],
            'export_type' => 'string|in:xlsx,csv',
        ];
    }
}