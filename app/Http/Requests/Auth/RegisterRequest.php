<?php

namespace App\Http\Requests\Auth;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Post(
 *     path="api/v3/user/register",
 *     tags={"User"},
 *     summary="Register User",
 *     operationId="register_user",
 *     security={{"sanctum": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="Redirect to main page"
 *     ),
 *     @OA\Response(
 *         response=302,
 *         description="Redirect to main page, if user is not admin"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=419,
 *         description="Page expired",
 *     ),
 * )
 *
 */

class RegisterRequest extends ApiRequest
{

    protected $redirectRoute = 'register';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
        // $phone = preg_replace("/[^0-9]/", '', $this->request->get('phone'));
        // $this->request->set('phone', (int)$phone);
    }

    public function messages(): array
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
            'password.min' => 'Минимальная длина - 8 символов',
            'password.confirmed' => 'Пароль должен быть подтвержден',
        ];
    }
}
