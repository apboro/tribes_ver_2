<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use OpenApi\Annotations as OA;


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

class ApiRegisterRequest extends ApiRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:40',
            'email' => 'required|email|unique:users',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages(): array
    {
        return [
            'email.required'=>trans('responses/validation.register.email_required'),
            'email.email'=>trans('responses/validation.login.email_incorrect_format'),
            'email.unique'=>trans('responses/validation.register.email_already_use'),
            'name.string' => trans('responses/validation.register.incorrect_format'),
            'name.max' => trans('responses/validation.register.name_max_length'),

            'mail.required' => trans('responses/validation.register.email_required'),

            'phone.required' => trans('responses/validation.register.phone_required'),
            'phone.integer' => trans('responses/validation.register.incorrect_format'),
            'phone.unique' => trans('responses/validation.register.phone_already_use'),

            'password.required' => trans('responses/validation.register.password_require'),
            'password.string' => trans('responses/validation.register.incorrect_format'),
            'password.min' => trans('responses/validation.register.password_min_length'),
            'password.confirmed' => trans('responses/validation.register.password_confirm'),
        ];
    }
}
