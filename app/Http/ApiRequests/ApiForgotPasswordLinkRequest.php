<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Post(
 *    path="/api/v3//user/password/forgot",
*     operationId="forgot-password",
*     summary="Send reset link to email",
 *    tags={"User"},
 *    @OA\Parameter(
 *     name="email",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *      type="string")
 *      ),
 *     @OA\Response(response=200, description="Reset link sent OK", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=400, description="Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/validation_error_response")),
 *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
 *     )
 */
class ApiForgotPasswordLinkRequest extends ApiRequest
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
            'email.email'=>$this->localizeValidation('login.email_incorrect_format'),
        ];
    }
}
