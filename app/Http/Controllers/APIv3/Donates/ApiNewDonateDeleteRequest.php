<?php

namespace App\Http\Controllers\APIv3\Donates;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 * path="/api/v3/donate/{id}",
 *  operationId="delete_donate",
 *  summary="Delete donate",
 *  security={{"sanctum": {} }},
 *  tags={"Donates"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiNewDonateDeleteRequest extends ApiRequest
{

}