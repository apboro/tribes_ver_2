<?php

namespace App\Http\Requests\Community;

use Illuminate\Foundation\Http\FormRequest;

class DonateSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
//            'hash' => ['required', 'string', 'exists:users,hash'],
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('send_to_community', $this->request->get('send_to_community') !== null);
        $this->request->set('isAutoPrompt', $this->request->get('donate_auto_prompt') !== null);
    }

    public function messages()
    {
        return [
//            'hash.required' => 'Хэш обязателен',
//            'hash.string' => 'Хэш должен быть строкой',
        ];
    }
}
