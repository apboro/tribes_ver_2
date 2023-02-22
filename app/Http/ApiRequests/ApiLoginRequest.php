<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *    path="/api/v3/user/login",
 *    operationId="login",
 *    summary="User login",
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
 *     @OA\Response(response=200, description="Login OK", @OA\JsonContent(ref="#/components/schemas/login_success_response")),
 *     @OA\Response(response=400, description="Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/validation_error_response")),
 *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     )
 */
//      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/standart_response")),
//      @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/standart_response")),
//      @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/standart_response")),
//      @OA\Response(response=404, description="Not Found", @OA\JsonContent(ref="#/components/schemas/standart_response")),

class ApiLoginRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages(): array
    {
        return [
            'email.required' => $this->localizeValidation('login.email_required'),
            'email.email'=>$this->localizeValidation('login.email_incorrect_format'),
            'password.required' => $this->localizeValidation('login.password_require'),
        ];
    }
}
