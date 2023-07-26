<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(
 *     path="/api/v3/publications/check_feedback/{id}",
 *     operationId="check feedback",
 *     summary="Check if feedback was made and how long time it is opened",
 *     security={{"sanctum": {} }},
 *     tags={"Publication"},
 *     @OA\Parameter(name="id",in="path",description="Webinar or Publication ID",required=true,@OA\Schema(type="integer")),
 *     @OA\Parameter(name="type",in="query",description="webinar or publication",required=true,@OA\Schema(type="string")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiCheckPostFeedbackRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'type' => 'string|in:webinar,publication'
        ];
    }

}