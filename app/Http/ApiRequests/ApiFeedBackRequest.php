<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *  path="/api/v3/feed-back",
 *  operationId="feed-back",
 *  summary="Send feedback ticket",
 *  security={{"sanctum": {} }},
 *  tags={"User"},
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="fb_email", type="string"),
 *               @OA\Property(property="fb_message", type="string"),
 *               @OA\Property(property="fb_phone", type="string"),
 *               @OA\Property(property="fb_name", type="string"),
 *         )
 *      ),
 *      @OA\Response(response=200, description="Feedback sent OK", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=400, description="Error: Bad Request"),
 *
 *)
 */
class ApiFeedBackRequest extends ApiRequest
{
   public function rules():array
    {
        return [
            'fb_email' => 'required|email',
            'fb_message' => 'required',
            'fb_phone'=> 'required',
            'fb_name'=> 'required'
        ];
    }

    public function messages():array
    {
        return [
            'fb_email.required' => $this->localizeValidation('register.email_required'),
            'fb_email.email' => $this->localizeValidation('login.email_incorrect_format'),
            'fb_message.required' => $this->localizeValidation('feed_back.text_required'),
            'fb_phone.required'=> $this->localizeValidation('phone.required'),
            'fb_name.required'=> $this->localizeValidation('name.required'),
        ];
    }
}
