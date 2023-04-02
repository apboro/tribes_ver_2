<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/manager/feed-back/show/{id}",
 *     operationId="admin-feed-back-show",
 *     summary="Show feed back from site",
 *     security={{"sanctum": {} }},
 *     tags={"Admin feed back"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="feedback ID in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiManagerFeedBackShowRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'id'=>'required|integer|min:1|exists:feedback'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('feed_back.id_required'),
            'id.integer' => $this->localizeValidation('feed_back.id_integer'),
            'id.exists' => $this->localizeValidation('feed_back.exists'),
        ];
    }
}
