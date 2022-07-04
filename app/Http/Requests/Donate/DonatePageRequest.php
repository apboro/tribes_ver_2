<?php

namespace App\Http\Requests\Donate;

use Illuminate\Foundation\Http\FormRequest;

class DonatePageRequest extends FormRequest
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
//            'amount' => ['required', 'integer'],
//            'currency' => ['required', 'integer']
        ];
    }
}
