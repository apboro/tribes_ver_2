<?php

namespace App\Http\Requests\Tariff;

use Illuminate\Foundation\Http\FormRequest;

class TariffRequest extends FormRequest
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
            'tariff_name' => ['string', 'max:255'],
            'tariff_cost' => ['integer', 'min:0']
        ];
    }

    public function messages()
    {
        return [
            'tariff_name.max' => 'Максимально количество знаков 255',
            'tariff_cost.min' => 'Стоимость не может быть отрицательным числом.',
        ];
    }
}