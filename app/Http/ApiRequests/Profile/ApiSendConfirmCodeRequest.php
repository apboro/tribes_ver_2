<?php

namespace App\Http\ApiRequests\Profile;



use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/user/phone/send-confirm-code",
 *  operationId="phone-send-confirm-code",
 *  summary="Send Confirm Phone Code",
 *  security={{"sanctum": {} }},
 *  tags={"User Phone"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="phone", type="integer"),
 *                 @OA\Property(property="code",type="string"),
 *                 example={"phone": 9500521558, "code": "+7"}
 *             )
 *      )
 * ),
 *      @OA\Response(response=200, description="Phone confirmed successfully", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *      @OA\Response(response=400, description="Error: Bad Request", @OA\JsonContent(ref="#/components/schemas/api_response_error")),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *
 *)
 */
class ApiSendConfirmCodeRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|integer|unique:users',
            'code' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => $this->localizeValidation('phone.required'),
            'phone.integer' => $this->localizeValidation('phone.incorrect_format'),
            'code.required' => $this->localizeValidation('phone.code_required'),
            'code.integer' => $this->localizeValidation('phone.code_incorrect_format'),
        ];
    }
}
