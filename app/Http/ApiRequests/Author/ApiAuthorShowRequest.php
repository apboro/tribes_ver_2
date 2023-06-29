<?php

namespace App\Http\ApiRequests\Author;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/authors/{id}",
 *     operationId="show-author-fields",
 *     summary= "Show author",
 *     security= {{"sanctum": {} }},
 *     tags= {"Author"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of author in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiAuthorShowRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
