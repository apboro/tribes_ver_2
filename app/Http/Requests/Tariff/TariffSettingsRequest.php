<?php

namespace App\Http\Requests\Tariff;

use Illuminate\Foundation\Http\FormRequest;

class TariffSettingsRequest extends FormRequest
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
            'reminder_description' => ['nullable', 'string', 'max:600'],
            'success_description'  => ['nullable', 'string', 'max:600'],
            'welcome_description'  => ['nullable', 'string', 'max:600'],
            'title'                => ['nullable', 'string', 'max:255'],
            'editor_data'          => ['nullable', 'string', 'max:10000'],
        ];
    }

    public function messages()
    {
        return [
            'reminder_description.max' => 'Максимально количество знаков 600',
            'success_description.max' => 'Максимально количество знаков 600',
            'welcome_description.max' => 'Максимально количество знаков 600',
            'title.max' => 'Максимально количество знаков 255',
            'editor_data.max' => 'Максимально количество знаков 10000',
        ];
    }
}
