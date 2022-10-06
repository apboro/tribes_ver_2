<?php

namespace App\Http\Requests\Tariff;

use Illuminate\Foundation\Http\FormRequest;

class TariffFormPayRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'communityTariffID' => ['required', 'exists:tarif_variants,id']
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages()
    {
        return [
            'communityTariffID.required' => 'Нет идентификатора тарифа',
            'communityTariffID.exists' => 'Такого тарифа нет или он более недоступен',
        ];
    }

}
