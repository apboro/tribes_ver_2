<?php

namespace App\Http\ApiRequests\Authentication;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *     path="/api/v3/user/password/forgot",
 *     operationId="forgot-password",
 *     summary="Send reset link to email",
 *     tags={"Authorization"},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="email", type="string"),
 *         )
 *      ),
 *
 *     @OA\Response(response=200, description="Reset link sent", @OA\JsonContent(ref="#/components/schemas/api_response_success")),
 *
 *     @OA\Response(response=422, description="Validation or sending error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *
 *     @OA\Response(response=500, description="Server error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiUserForgotPasswordLinkRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
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
        ];
    }
}
