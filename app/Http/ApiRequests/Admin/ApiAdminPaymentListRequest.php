<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/manager/payments",
 *     tags={"Admin payments"},
 *     summary="Show payments list",
 *     operationId="admin-payments-list",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiAdminPaymentListRequest extends ApiRequest
{
}
