<?php

namespace App\Http\Requests\Donate;

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
            'success_description' => ['sometimes', 'max:600'],
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('settingsUpdate', $this->request->get('settingsUpdate') !== null);
        $this->request->set('isAutoPrompt', $this->request->get('donate_auto_prompt') !== null);
    }

    public function messages()
    {
        return [
            'success_description.max' => 'Максимальное количество знаков поля "Текст сообщения" 600',
        ];
    }
}