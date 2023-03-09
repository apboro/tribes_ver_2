<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v3/user/password/reset",
 *     operationId="reset-password",
 *     summary="Reset user password via reset link",
 *     tags={"Authorizathion"},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="email", type="string"),
 *               @OA\Property(property="password", type="string"),
 *               @OA\Property(property="password_confirmation", type="string"),
 *               @OA\Property(property="token", type="string"),
 *         )
 *      ),
 *
 *     @OA\Response(response=200, description="Password reset success",
 *         @OA\JsonContent(
 *            @OA\Property(property="data", type="array",
 *                @OA\Items(
 *                    @OA\Property(property="token", type="string"),
 *                ),
 *            ),
 *            @OA\Property(property="message", type="string", nullable=true),
 *            @OA\Property(property="payload", type="array", @OA\Items(), example={})
 *         )
 *     ),
 *
 *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *
 *     @OA\Response(response=500, description="Server error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 * )
 */
class ApiUserResetPasswordRequest extends ApiRequest
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
