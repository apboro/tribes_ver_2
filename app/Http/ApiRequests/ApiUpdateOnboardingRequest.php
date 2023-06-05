<?php

namespace App\Http\ApiRequests;

use Illuminate\Support\Str;

/**
 * @OA\Post(
 * path="/api/v3/onboarding/edit",
 * operationId="Update_onboarding",
 * summary= "Update onboarding",
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
 *              @OA\Property(property="onboarding_uuid", type="string"),
 *              @OA\Property(property="rules", type="object"),
 *              @OA\Property(property="title", type="string"),
 *              @OA\Property(property="greeting_message_text",type="string"),
 *              @OA\Property(property="greeting_image", type="file", format="binary"),
 *              @OA\Property(property="question_image", type="file", format="binary"),
 *              @OA\Property(property="greeting_image_delete", type="boolean"),
 *              @OA\Property(property="question_image_delete", type="boolean"),
 *              @OA\Property(property="communities_ids[]",type="array",@OA\Items(type="integer"))
 *         )
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */

class ApiUpdateOnboardingRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'onboarding_uuid' => 'required|exists:onboardings,uuid',
            'rules'=>'json|required',
            'title' =>'required|string',
            'greeting_image' => 'image|nullable',
            'question_image' => 'image|nullable',
            'greeting_image_delete' => 'boolean',
            'question_image_delete' => 'boolean',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
        ];
    }

    public function prepareForValidation(): void
    {
        if($this->community_ids) {
            if (Str::contains($this->community_ids[0], ',')) {
                $this->merge([
                    'community_ids' => explode(',', $this->community_ids[0])
                ]);
            }
        }
        $this->merge([
            'greeting_image_delete' => $this->toBoolean($this->greeting_image_delete)
        ]);
        $this->merge([
            'question_image_delete' => $this->toBoolean($this->question_image_delete)
        ]);
    }


    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    public function messages(): array
    {
        return [
        ];
    }

}