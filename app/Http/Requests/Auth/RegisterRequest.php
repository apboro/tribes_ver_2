<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    protected $redirectRoute = 'register';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }

    public function prepareForValidation()
    {
        $this->request->set('email', strtolower($this->request->get('email')));
        // $phone = preg_replace("/[^0-9]/", '', $this->request->get('phone'));
        // $this->request->set('phone', (int)$phone);
    }

    public function messages()
    {
        return [
            // 'name.required' => 'Имя обязательно для заполнения',
            'name.string' => 'Неверный формат',
            'name.max' => 'Максимальная длинная 100 символов',

            'mail.required' => 'email обязателен для заполнения',

            'phone.required' => 'Телефон обязателен для заполнения',
            'phone.integer' => 'Неверный формат',
            'phone.unique' => 'Номер телефона занят',

            'password.required' => 'Пароль обязателен для заполнения',
            'password.string' => 'Неверный формат',
            'password.min' => 'Минимальная длинна - 8 символов',
            'password.confirmed' => 'Пароль должен быть подтвержден',
        ];
    }
}
