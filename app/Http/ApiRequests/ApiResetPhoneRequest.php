<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *  path="/api/v3/user/phone/reset-confirmed",
 *  operationId="reset-phone",
 *  summary="Reset confirmed phone",
 *  security={{"sanctum": {} }},
 *  tags={"User Phone"},
 *
 *      @OA\Response(response=200, description="Phone reset successfully", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *      @OA\Response(response=400, description="Error: Bad Request", @OA\JsonContent(ref="#/components/schemas/api_response_error")),
 *
 *)
 * */
class ApiResetPhoneRequest extends ApiRequest
{

}