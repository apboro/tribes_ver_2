<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Post(path="/api/v3/chats/tags",
 *     tags={"Chats Tags"},
 *     summary="Store tag for chats",
 *     operationId="StoreTagForChats",
 *     security={{"sanctum": {} }},
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *              @OA\Property(property="name", type="string", example="Bubbles")
 *          )
 *      ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */

class ApiTagStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:1|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => $this->localizeValidation('tag.name_required'),
            'name.min' => $this->localizeValidation('tag.name_min'),
            'name.max' => $this->localizeValidation('tag.name_min'),
        ];
    }
}
