<?php

namespace App\Http\ApiRequests;

class ApiRegisterRequest extends ApiRequest
{

    /**
     * @OA\Post(
     *    path="/api/v3/user/register",
     *    operationId="register",
     *    summary="User register",
     *    tags={"User"},
     *    @OA\Parameter(
     *     name="email",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *      type="string")
     *      ),
     *   @OA\Parameter(
     *     name="name",
     *      in="path",
     *      required=false,
     *      @OA\Schema(
     *      type="string")
     *      ),
     *     @OA\Response(response=200, description="Register OK", @OA\JsonContent(ref="#/components/schemas/register_success_response")),
     *     @OA\Response(response=400, description="Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
     *     @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/validation_error_response")),
     *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
     *     )
     * )
     */

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
