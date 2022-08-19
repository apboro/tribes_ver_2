<?php

namespace App\Http\Requests\Community;

use Illuminate\Foundation\Http\FormRequest;

class DonateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'donate.0.cost' => 'exclude_without:donate.0.status|required',
            'donate.1.cost' => 'exclude_without:donate.1.status|required',
            'donate.2.cost' => 'exclude_without:donate.2.status|required',
            'donate.3.min_price' => 'exclude_without:donate.3.status|required',
            'donate.3.max_price' => 'exclude_without:donate.3.status|required|gte:donate.3.min_price',

            'title' => ['sometimes ', 'required', 'max:255'],
            'description' => ['sometimes', 'required', 'max:600'],
            'success_description' => ['sometimes', 'max:600'],

            'entity' => 'nullable',
            'entityId' => 'nullable',
            'entityModel' => 'nullable',
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('settingsUpdate', $this->request->get('settingsUpdate') !== null);
        $this->request->set('send_to_community', $this->request->get('send_to_community') !== null);
        $this->request->set('isAutoPrompt', $this->request->get('donate_auto_prompt') !== null);
    }

    public function messages()
    {
        return [
            'donate.0.cost.required' => 'Поле "Сумма", в первой кнопке, обязательно для заполнения.',
            'donate.1.cost.required' => 'Поле "Сумма", во второй кнопке, обязательно для заполнения.',
            'donate.2.cost.required' => 'Поле "Сумма", в третьей кнопке, обязательно для заполнения.',
            'donate.3.min_price.required' => 'Поле "Мин. сумма", в произвольной сумме, обязательно для заполнения.',
            'donate.3.max_price.required' => 'Поле "Макс. сумма", в произвольной сумме, обязательно для заполнения.',
            'donate.3.max_price.gte' => 'Поле "Макс. сумма", должно быть больше или равно чем "Мин. сумма".',

            'title.required' => 'Поле "Наименование доната", обязательно для заполнения.',
            'title.max' => 'Максимальное количество знаков поля "Наименование доната" 255',

            'description.required' => 'Поле "Общее описание доната", обязательно для заполнения.',
            'description.max' => 'Максимальное количество знаков поля "Общее описание доната" 600',

            'success_description.max' => 'Максимальное количество знаков поля "Текст сообщения" 600',
        ];
    }
}
