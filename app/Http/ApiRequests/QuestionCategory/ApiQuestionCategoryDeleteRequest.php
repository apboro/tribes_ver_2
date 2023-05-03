<?php


namespace App\Http\ApiRequests\QuestionCategory;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 *  path="/api/v3/question-category/{id}",
 *  operationId="Question-category-delete",
 *  summary="Delete the specified question category",
 *  security={{"sanctum": {} }},
 *  tags={"Question Category"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Question category ID",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionCategoryDeleteRequest extends ApiRequest
{
    public function all($keys = null): array
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }
}