<?php

namespace App\Http\ApiRequests\Author;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/author/{id}",
 *     operationId="show-author-for-followers",
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
class ApiAuthorShowForFollowersRequest extends ApiRequest
{

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:authors,id'
        ];
    }
}
