<?php

namespace App\Http\Requests\Auth;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *     path="api/v3/user/password/change",
 *     tags={"User"},
 *     summary="User change password",
 *     operationId="change_password",
 *     security={{"sanctum": {} }},
 *     @OA\RequestBody(
 *         required=false,
 *         description="Тело запроса для смены пароля",
 *         @OA\JsonContent(
 *              @OA\Property(
 *                  property="password",
 *                  type="string",
 *              ),
 *         ),
 *     ),
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

class ChangePassRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Пароль обязателен для заполнения',
            'password.string' => 'Неверный формат',
            'password.min' => 'Минимальная длина - 8 символов',
            'password.confirmed' => 'Пароль должен быть подтвержден',
        ];
    }
}
