<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Post(
*   path="/api/v3/user/password/reset",
*   operationId="reset-password",
*   summary="Reset password",
 *    tags={"User"},
 *    @OA\Parameter(
 *     name="email",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *      type="string")
 *      ),
 *   @OA\Parameter(
 *     name="password",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *      type="string")
 *      ),
 *     @OA\Response(response=200, description="Password reset and Login OK", @OA\JsonContent(ref="#/components/schemas/password_reset_success_response")),
 *     @OA\Response(response=400, description="Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/validation_error_response")),
 *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     )
 */

class ApiResetPasswordLinkRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages(): array
    {
        return [
            'email.required' => $this->localizeValidation('register.email_required'),
            'email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'password.required' => $this->localizeValidation('login.password_require'),
            'password.confirmed' => $this->localizeValidation('reset_password.password_confirmed'),
            'password.min' => $this->localizeValidation('reset_password.password_length'),
            'token.required' => $this->localizeValidation('reset_password.token_required'),
        ];
    }
}
