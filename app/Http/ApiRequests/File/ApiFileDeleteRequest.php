<?php

namespace App\Http\ApiRequests\File;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Delete(
 *     path="/api/v3/file/{id}",
 *     tags={"Files"},
 *     summary="Delete files",
 *     operationId="files-delete",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of file in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiFileDeleteRequest extends ApiRequest
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
            'id' => 'required|integer|min:1'
        ];
    }
}
