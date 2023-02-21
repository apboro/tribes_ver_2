<?php

namespace App\Http\Requests\Auth;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Attributes as OAT;
/**
#[OAT\Schema(
    schema: "ApiResponseCommon_login",
    title: "ApiResponse Common for login",
    description: "Login Api Response with data, message and payload",
    properties: [
        new OAT\Property(property: 'data', type: 'array', items: new OAT\Items(ref: '#/components/schemas/UserResource')),
        new OAT\Property(property: 'message', type: 'string'),
        new OAT\Property(property: 'payload', type: 'array', items: new OAT\Items(), example: []),
    ]
)]

#[OAT\Post(
    path: '/api/v3/user/login',
    operationId: "login",
    summary: "User login",
    security: [["sanctum" => []]],
    tags: ['User'],
    parameters: [
        new OAT\Parameter(name: 'email', in: 'path', required: true, schema: new OAT\Schema(type: 'string')),
        new OAT\Parameter(name: 'password', in: 'path', required: true, schema: new OAT\Schema(type: 'string'))
    ],
    responses: [
        new OAT\Response(
            response: 200,
            description: 'Login OK response',
            content: new OAT\JsonContent(ref: '#/components/schemas/ApiResponseCommon_login'),
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 301,
            ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 304,
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 400,
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 401
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 403,
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 404,
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 419,
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 422,
        ),
        new OAT\Response(
            ref: '#/components/responses/response_with_message_and_payload',
            response: 500,
        ),
    ],
)]
 */
class LoginRequest extends ApiRequest
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
            'email.required' => 'email - обязательное поле',
            'password.required' => 'Пароль - обязательное поле',
        ];
    }
}
