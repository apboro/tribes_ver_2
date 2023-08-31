<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/webinars/pay/{uuid}",
 *  operationId="Webinar-pay",
 *  summary="Pay Webinar by uuid",
 *  security={{"sanctum": {} }},
 *  tags={"Webinar"},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         description="Webinar uuid",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="05a47c05-598a-4581-8315-1da5a5b5f92a"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="email", type="string", example="test@test.com"),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK",@OA\JsonContent())
 *)
 */
final class ApiWebinarPayRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'email' => 'required|email',
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
