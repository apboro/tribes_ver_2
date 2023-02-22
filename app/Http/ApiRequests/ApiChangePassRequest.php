<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Post(
 *    path="/api/v3/user/password/change",
 *    operationId="change-password",
 *    summary="User change password",
 *    tags={"User"},
 *   @OA\Parameter(
 *     name="password",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *      type="string")
 *      ),
 *     @OA\Response(response=200, description="Password Change OK", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=400, description="Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/validation_error_response")),
 *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *   )
 */
class ApiChangePassRequest extends ApiRequest
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
