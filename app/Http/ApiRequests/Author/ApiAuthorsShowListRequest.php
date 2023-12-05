<?php

namespace App\Http\ApiRequests\Author;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/authors/list",
 *     operationId="show-authors-list",
 *     summary= "Show authors list",
 *     tags= {"Authors list"},
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiAuthorsShowListRequest extends ApiRequest
{

}
