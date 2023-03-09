<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v3/user/password/change",
 *     operationId="change-password",
 *     summary="Change user password",
 *     tags={"Authorizathion"},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="password", type="string"),
 *               @OA\Property(property="password_confirmation", type="string"),
 *         )
 *      ),
 *
 *     @OA\Response(response=200, description="Password changed", @OA\JsonContent(ref="#/components/schemas/api_response_success")),
 *
 *     @OA\Response(response=401, description="User not authorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *
 *     @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *
 *     @OA\Response(response=500, description="Server error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 *   )
 */
class ApiUserChangePassRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => $this->localizeValidation('register.password_require'),
            'password.string' => $this->localizeValidation('register.incorrect_format'),
            'password.min' => $this->localizeValidation('register.password_min_length'),
            'password.confirmed' => $this->localizeValidation('register.password_confirm'),
        ];
    }
}
