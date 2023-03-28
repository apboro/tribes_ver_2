<?php

namespace App\Http\ApiRequests\Profile;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 *  path="/api/v3/payment-cards",
 *  operationId="payment-card-delete",
 *  summary="Delete bank card",
 *  security={{"sanctum": {} }},
 *  tags={"User Cards"},
 *  @OA\RequestBody(
 *          @OA\JsonContent(
 *                 @OA\Property(property="card_id",type="integer"),
 *                example={"card_id": 2432423424543}
 *      )
 * ),
 *      @OA\Response(response=200, description="Cards fetched", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=400, description="Error: Bad Request"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *
 *)
 */
class ApiPaymentCardDeleteRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'card_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'card_id.required' => $this->localizeValidation('payment.card_id_required'),
        ];
    }
}
