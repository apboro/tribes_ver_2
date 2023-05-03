<?php


namespace App\Http\ApiRequests\QuestionCategory;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get (
 *  path="/api/v3/question-category",
 *  operationId="question-categories-list",
 *  summary="List of question categories",
 *  security={{"sanctum": {} }},
 *  tags={"Question Category"},
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionCategoryListRequest extends ApiRequest
{
}