<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/manager/feed-back/answer",
 *  operationId="admin-feed-back-answer",
 *  summary="Add answer to feed back from site",
 *  security={{"sanctum": {} }},
 *  tags={"Admin feed back"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     example="1"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     example="test answer"
 *                 ),
 *             ),
 *         )
 *     ),
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiManagerFeedBackAnswerRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1|exists:feedback',
            'message' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('feed_back.id_required'),
            'id.integer' => $this->localizeValidation('feed_back.id_integer'),
            'id.exists' => $this->localizeValidation('feed_back.exists'),
            'message.required' => $this->localizeValidation('feed_back.text_required'),
        ];
    }
}
