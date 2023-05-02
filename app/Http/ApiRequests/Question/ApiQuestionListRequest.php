<?php


namespace App\Http\ApiRequests\Question;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get (
 *  path="/api/v3/question/list/{id}",
 *  operationId="question-list",
 *  summary="List of questions by knowledge id",
 *  security={{"sanctum": {} }},
 *  tags={"Question"},
 *    @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of related knowledge in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="question_status",
 *         in="query",
 *         description="Question status for filter",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="category_id",
 *         in="query",
 *         description="Category id for filter",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiQuestionListRequest extends ApiRequest
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
            'category_id' => ['sometimes','nullable','integer'],
            'question_status' => ['sometimes','nullable','string','in:draft,draft_auto,published'],
        ];
    }
}