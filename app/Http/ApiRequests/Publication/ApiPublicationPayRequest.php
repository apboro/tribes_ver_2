<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/publication/pay/{uuid}",
 *  operationId="publication-pay",
 *  summary="Pay publication by uuid",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="email", type="string", example="test@test.com"),
 *                 @OA\Property(property="hash",  type="string", example="1qwe123"),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK",@OA\JsonContent())
 *)
 */
class ApiPublicationPayRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'email' => 'required|email',
            'uuid' => 'required'
        ];
    }

    public function prepareForValidation():void
    {
        $this->request->set('email', strtolower($this->request->get('email')));
    }

    public function messages():array
    {
        return [
            'email.required' => $this->localizeValidation('login.email_required'),
            'email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'hash.required'=> $this->localizeValidation('course.hash_required')
        ];
    }
}
