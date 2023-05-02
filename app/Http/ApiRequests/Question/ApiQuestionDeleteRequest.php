<?php


namespace App\Http\ApiRequests\Question;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 *  path="/api/v3/question/{id}",
 *  operationId="Question-delete",
 *  summary="Delete the specified question",
 *  security={{"sanctum": {} }},
 *  tags={"Question"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Question ID",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionDeleteRequest extends ApiRequest
{
    public function all($keys = null): array
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }
}