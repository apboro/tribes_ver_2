<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *     path="/api/v3/user/register",
 *     operationId="register",
 *     summary="Register user",
 *     tags={"Authorizathion"},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="email", type="string"),
 *               @OA\Property(property="name", type="string"),
 *         )
 *      ),
 *
 *     @OA\Response(response=200, description="Registration successful", @OA\JsonContent(
 *            @OA\Property(property="data", type="array",
 *                @OA\Items(
 *                    @OA\Property(property="token", type="string"),
 *                ),
 *            ),
 *            @OA\Property(property="message", type="string", nullable=true),
 *            @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *     ),
 *
 *     @OA\Response(response=422, description="Wrong credentials", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *
 *     @OA\Response(response=500, description="Server error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 * )
 */
class ApiUserRegisterRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => '',
            'email' => '',
        ];
    }

    public function prepareForValidation(): void
    {
        $email = strtolower($this->request->get('email'));
        $this->request->set('email', $email);
        $name = $this->request->get('name');
        if (empty($name)) {
            $name = explode('@', $email);
            $this->request->set('name', $name[0] ?? 'No name yet');
        }
    }

    public function messages(): array
    {
        return [
            'email.required' => $this->localizeValidation('register.email_required'),
            'email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'email.unique' => $this->localizeValidation('register.email_already_use'),
            'name.string' => $this->localizeValidation('register.incorrect_format'),
            'name.max' => $this->localizeValidation('register.name_max_length'),

            'mail.required' => $this->localizeValidation('register.email_required'),

            'phone.required' => $this->localizeValidation('register.phone_required'),
            'phone.integer' => $this->localizeValidation('register.incorrect_format'),
            'phone.unique' => $this->localizeValidation('register.phone_already_use'),

            'password.required' => $this->localizeValidation('register.password_require'),
            'password.string' => $this->localizeValidation('register.incorrect_format'),
            'password.min' => $this->localizeValidation('register.password_min_length'),
            'password.confirmed' => $this->localizeValidation('register.password_confirm'),
        ];
    }

    public function passedValidation(): void
    {
        $email = $this->request->get('email');
        $name = $this->request->get('name');

        if (empty($name)) {
            $name = explode('@', $email);
        }

        $this->merge([
            'email' => strtolower($email),
            'name' => $name,
        ]);
    }
}
