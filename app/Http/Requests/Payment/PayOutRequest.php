<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PayOutRequest extends FormRequest
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
            'CardId' => ['required'],
            'accumulationId' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'CardId.required' => 'Номер карты обязателен для заполнения',
            'accumulationId.required' => 'ID накопления обязателен для заполнения',
        ];
    }
}
