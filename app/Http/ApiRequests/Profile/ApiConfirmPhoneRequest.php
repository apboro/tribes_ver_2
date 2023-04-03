<?php

namespace App\Http\ApiRequests\Profile;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/user/phone/confirm",
 *  operationId="confirm-phone",
 *  summary="Confirm Phone",
 *  security={{"sanctum": {} }},
 *  tags={"User Phone"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="phone", type="integer", example=79500168570),
 *                 @OA\Property(property="sms_code",type="integer", example=4356),
 *                 ),
 *             ),
 *      ),
 *      @OA\Response(response=200, description="Phone confirmed successfully", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *      @OA\Response(response=400, description="Error: Bad Request", @OA\JsonContent(ref="#/components/schemas/api_response_error")),
 *      @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiConfirmPhoneRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'phone' => 'required|integer',
            'sms_code'  => 'required|integer'
        ];
    }

    public function messages():array
    {
        return [
            'sms_code.required' => $this->localizeValidation('phone.sms_code_required'),
            'sms_code.integer' => $this->localizeValidation('phone.sms_code_not_valid'),
        ];
    }
}
