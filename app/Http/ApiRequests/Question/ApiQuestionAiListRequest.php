<?php

namespace App\Http\ApiRequests\Question;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\Get (
 *  path="/api/v3/question/list",
 *  operationId="questions-ai-list",
 *  summary="List of questions on Ai",
 *  security={{"sanctum": {} }},
 *  tags={"Question"},
 *
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionAiListRequest extends ApiRequest
{
    public function rules(): array
    {
        return [];
    }
}
