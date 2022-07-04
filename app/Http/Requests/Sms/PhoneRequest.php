<?php

namespace App\Http\Requests\Sms;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['integer'],
        ];
    }

    public function prepareForValidation()
    {
        // $phone = preg_replace("/[^0-9]/", '', $this->request->get('phone'));
        // $this->request->set('phone', (int)$phone);
    }

    public function messages()
    {
        return [
            'phone.integer' => 'Неверный формат номера телефона',
            // 'phone.unique' => 'Номер телефона занят',
        ];
    }
}