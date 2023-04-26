<?php

namespace App\Http\ApiRequests;

/**
* @OA\Post(
 *     path="/api/v3/send_demo_email",
 *     operationId="send_email",
 *     summary="Sends email",
 *     tags={"Send Email"},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="email", type="string"),
 *               @OA\Property(property="phone", type="string"),
 *               @OA\Property(property="name", type="string"),
 *               @OA\Property(property="text", type="string"),
 *         )
 *      ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiSendEmailRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'email' =>'required|email',
            'phone' => 'required',
            'name' => 'required',
            'text' => 'required|string|max:250'
        ];

    }

}