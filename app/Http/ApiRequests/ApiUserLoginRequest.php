<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *      summary="Login user",
 *      path="/api/v3/user/login",
 *      operationId="login",
 *      tags={"Authorizathion"},
 *
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="email", type="string"),
 *               @OA\Property(property="password", type="string"),
 *         )
 *      ),
 *
 *      @OA\Response(response=200, description="Login success", @OA\JsonContent(
 *             @OA\Property(property="data", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="token", type="string"),
 *                 ),
 *             ),
 *             @OA\Property(property="message", type="string", nullable=true),
 *             @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *
 *      @OA\Response(response=422, description="Wrong credentials", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *
 *      @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 * )
 */
class ApiUserLoginRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required',
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
            'email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'password.required' => $this->localizeValidation('login.password_require'),
        ];
    }
}
