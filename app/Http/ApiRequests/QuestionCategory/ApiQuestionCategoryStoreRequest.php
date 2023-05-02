<?php


namespace App\Http\ApiRequests\QuestionCategory;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/question-category",
 *  operationId="question-category-add",
 *  summary="Add question category",
 *  security={{"sanctum": {} }},
 *  tags={"Question Category"},
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
class ApiQuestionCategoryStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:120'],
        ];
    }
}