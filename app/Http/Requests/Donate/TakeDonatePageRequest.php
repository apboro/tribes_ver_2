<?php

namespace App\Http\Requests\Donate;

use Illuminate\Foundation\Http\FormRequest;

class TakeDonatePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'amount' => ['required', 'integer'],
            'community_id' => ['required', 'integer', 'exists:communities,id']
        ];
    }

    public function messages()
    {
        return [
            
            
        ];
    }
}
