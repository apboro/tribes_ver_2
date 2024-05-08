<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/story/{id}",
 *     operationId="show-story-fields",
 *     summary= "Show story",
 *     security= {{"sanctum": {} }},
 *     tags= {"Stories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of story in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiStoryShowRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return [
                'id' => $this->route('id'),
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:stories,id',
        ];
    }

}