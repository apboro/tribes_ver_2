<?php


namespace App\Http\ApiRequests\QuestionCategory;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Put(
 *  path="/api/v3/question-category/{id}",
 *  operationId="question-category-update",
 *  summary="Update the specified question category",
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
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="name",type="string"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionCategoryUpdateRequest extends ApiRequest
{
    public function all($keys = null): array
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:120'],
        ];
    }
}