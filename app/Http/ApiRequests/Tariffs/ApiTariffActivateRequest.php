<?php

namespace App\Http\ApiRequests\Tariffs;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Patch(
 *  path="/api/v3/tariff/setActivity",
 *  operationId="tariff-activation-deactivation",
 *  summary="Tariff Activation-Deactivation",
 *  security={{"sanctum": {} }},
 *  tags={"Tariffs"},
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *                 @OA\Property(property="community_ids", type="array", @OA\Items(type="integer")),
 *                 @OA\Property(property="is_active", type="boolean", example="true"),
 *          ),
 *      ),
 *      @OA\Response(response=200, description="OK"),
 *      @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *      @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *     )
 */
class ApiTariffActivateRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'community_ids' => 'required|array',
            'community_ids.*' => 'integer|exists:communities,id',
            'is_active' => 'boolean|required',
        ];
    }

}