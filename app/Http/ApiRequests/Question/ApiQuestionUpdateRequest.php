<?php


namespace App\Http\ApiRequests\Question;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/question/{id}",
 *  operationId="question-update",
 *  summary="Update the specified question",
 *  security={{"sanctum": {} }},
 *  tags={"Question"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="question_status",type="string"),
 *                 @OA\Property(property="question_text",type="string"),
 *                 @OA\Property(property="answer_text",type="string"),
 *                 @OA\Property(property="question_image",type="file"),
 *                 @OA\Property(property="answer_image",type="file"),
 *                 ),
 *             ),

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
            'question_status' => ['string','in:draft,draft_auto,published'],
            'question_text' => ['required','string','max:4096'],
            'answer_text' => ['required','string','max:4096'],
            'question_image' => ['sometimes','nullable','image'],
            'answer_image' => ['sometimes','nullable','image'],
        ];
    }
}