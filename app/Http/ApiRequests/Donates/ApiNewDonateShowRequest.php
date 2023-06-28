<?php

namespace App\Http\ApiRequests\Donates;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\Get(path="/api/v3/donate/{id}",
 *     tags={"Donates"},
 *     summary="Show donate",
 *     operationId="show-donate",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="id of donate",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiNewDonateShowRequest extends ApiRequest
{

}