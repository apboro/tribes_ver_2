<?php

namespace App\Http\ApiRequests;

/**
 * @OA\POST(
 * path="/api/v3/onboarding",
 * operationId="Store_onboarding",
 * summary= "Store onboarding",
 * security= {{"sanctum": {} }},
 * tags= {"Onboarding"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             encoding={
 *                  "communities_ids[]": {
 *                      "explode": true,
 *                  }
 *            },
 *          @OA\Schema(
 *              @OA\Property(property="rules", type="object"),
 *              @OA\Property(property="title", type="string"),
 *              @OA\Property(property="greeting_message_text",type="string"),
 *              @OA\Property(property="image", type="file", format="binary"),
 *              @OA\Property(property="communities_ids[]",type="array",@OA\Items(type="integer"))
 *         )
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */

class ApiStoreOnboardingRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'rules'=>'json|required',
            'title' =>'required|string',
            'greeting_message_text' => 'required|string|max:4000',
            'image' => 'image|mimetypes:image/jpeg,image/png,image/gif,image/svg',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
        ];
    }

}