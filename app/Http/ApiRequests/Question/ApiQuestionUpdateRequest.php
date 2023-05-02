<?php


namespace App\Http\ApiRequests\Question;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Put(
 *  path="/api/v3/question/{id}",
 *  operationId="question-update",
 *  summary="Update the specified question",
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
 *     @OA\RequestBody(
 *         description="
 *          question_status - enum from [draft,draft_auto,published]
 *          overlap - enum from [full,part]",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="question_status",type="string"),
 *                 @OA\Property(property="category_id",type="integer"),
 *                 @OA\Property(property="overlap",type="string"),
 *                 @OA\Property(property="question_text",type="string"),
 *                 @OA\Property(property="answer_text",type="string"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionUpdateRequest extends ApiRequest
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
            'question_status' => ['required','string','in:draft,draft_auto,published'],
            'category_id' => ['required','integer'],
            'overlap' => ['required','string','in:full,part'],
            'question_text' => ['required','string','max:4096'],
            'answer_text' => ['required','string','max:4096'],
        ];
    }
}