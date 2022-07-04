<?php

namespace App\Http\Requests\API;

use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property $community_id
 * @property array $filter
 */
class CommunitiesRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        //$this->filter = $this->filter ?? [];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

}
