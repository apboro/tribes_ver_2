<?php

namespace App\Http\ApiRequests;

use Illuminate\Support\Str;

/**
 * @OA\Post(
 * path="/api/v3/onboarding",
 * operationId="Store_onboarding",
 * summary= "Store onboarding",
 * security= {{"sanctum": {} }},
 * tags= {"Chats Onboarding"},
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
 *              @OA\Property(property="greeting_image", type="file", format="binary"),
 *              @OA\Property(property="question_image", type="file", format="binary"),
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
            'greeting_image' => 'image|nullable',
            'question_image' => 'image|nullable',
            'communities_ids' => 'array',
            'communities_ids.*' => 'integer|exists:communities,id',
        ];
    }

    public function prepareForValidation(): void
    {
        if($this->communities_ids) {
            if (Str::contains($this->communities_ids[0], ',')) {
                $this->merge([
                    'community_ids' => explode(',', $this->community_ids[0])
                ]);
            }
        }
    }
}