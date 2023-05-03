<?php


namespace App\Http\ApiRequests\Question;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/question",
 *  operationId="question-add",
 *  summary="Add question",
 *  security={{"sanctum": {} }},
 *  tags={"Question"},
 *     @OA\RequestBody(
 *         description="
 *          question_status - enum from [draft,draft_auto,published]",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="knowledge_id",type="integer"),
 *                 @OA\Property(property="question_status",type="string"),
 *                 @OA\Property(property="category_id",type="integer"),
 *                 @OA\Property(property="question_text",type="string"),
 *                 @OA\Property(property="answer_text",type="string"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'knowledge_id' => ['required','integer'],
            'question_status' => ['required','string','in:draft,draft_auto,published'],
            'category_id' => ['required','integer'],
            'question_text' => ['required','string','max:4096'],
            'answer_text' => ['required','string','max:4096'],
        ];
    }
}