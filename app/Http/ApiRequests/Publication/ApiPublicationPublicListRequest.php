<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\GET(
 *  path="/api/v3/public/publications/{author}",
 *  operationId="public-publication-list",
 *  summary="Public List publication",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *     @OA\Parameter(
 *         name="author",
 *         in="path",
 *         description="ID of author of publication",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationPublicListRequest extends ApiRequest
{

}