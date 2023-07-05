<?php

namespace App\Http\ApiRequests\Donates;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Post(
 *  path="/api/v3/pay/donate",
 *  operationId="pay-donate",
 *  summary="Pay donate with random sum",
 *  security={{"sanctum": {} }},
 *  tags={"Donates"},
 *    @OA\Parameter(
 *         name="XDEBUG_SESSION_START",
 *         in="query",
 *         description="uri_hash of knowledge",
 *         required=true,
 *          example="PHPSTORM",
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *                 @OA\Property(property="amount",type="string",example="100"),
 *                 @OA\Property(property="telegram_user_id",type="boolean",example="12865424"),
 *                 @OA\Property(property="donate_id",type="boolean",example="12"),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiDonatePageRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer'],
            'telegram_user_id' =>'required|integer',
            'donate_id' => 'required|integer|exists:donates,id'
        ];
    }
}
