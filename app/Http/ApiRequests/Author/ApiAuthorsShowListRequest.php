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
 *     @OA\Parameter(name="name",in="query",description="Search by name",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiAuthorsShowListRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
            'name' => 'nullable|string',
        ];
    }
}
