<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Post(
 *  path="/api/v3/lms_feedback/{publication}",
 *  operationId="lms_feedback_store",
 *  summary="Add feedback to LMS",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *     @OA\Parameter(name="publication", in="path", description="Publication ID", required=false,@OA\Schema(type="integer")),
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *                 @OA\Property(
 *                     property="like_material",
 *                     type="string",
 *                     description="['yes', 'no', 'neutral']",
 *                 ),
 *                 @OA\Property(
 *                     property="enough_material",
 *                     type="string",
 *                     description="['enough', 'not_enough', 'too_many']",
 *                 ),
 *                @OA\Property(
 *                     property="what_to_add",
 *                     description="Если all_ok = true, присылать пустой массив options",
 *                     type="object",
 *                 ),
 *               @OA\Property(
 *                     property="what_to_remove",
 *                     description="Если all_ok = true, присылать пустой массив options",
 *                     type="object",
 *                 ),
 *              example={"like_material": "yes", "enough_material": "not_enough", "what_to_add": {"all_ok": true, "options": {"add_audio_video", "add_images", "add_text", "make_webinar"} },"what_to_remove": {"all_ok": false,"options": {"not_interesting", "less_audio", "less_video", "less_images", "less_text", "less_webinars"}}}
 *            ),
 *     ),
 * @OA\Response(response=200, description="OK")
 *)
 */
class LMSFeedbackRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'like_material' => 'string',
            'enough_material'=> 'string',
        ];
    }

}
