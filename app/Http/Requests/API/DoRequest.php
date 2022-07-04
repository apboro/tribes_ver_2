<?php

namespace App\Http\Requests\API;

use App\Rules\Knowledge\OwnCommunityRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class DoRequest extends FormRequest
{
    private array $commands = [
        'delete',
        'update_draft',
        'update_publish',
    ];

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
            'community_id' => ['required', 'integer', new OwnCommunityRule()],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'command' => ['required', Rule::in($this->commands)],
            'params' => [
                'array:mark',
            ],
            'params.mark' => 'boolean',
        ];
    }
}