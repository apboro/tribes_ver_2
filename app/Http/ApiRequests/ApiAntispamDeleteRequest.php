<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 * path="/api/v3/antispam/{antispam_uuid}",
 *  operationId="delete_antispam_rule",
 *  summary="Delete antispam rule",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Antispam"},
 *     @OA\Parameter(
 *         name="antispam_uuid",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiAntispamDeleteRequest extends ApiRequest
{

}