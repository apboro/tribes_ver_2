<?php

namespace App\Http\ApiRequests\Payment;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\POST(path="/api/v3/bill/subscription",
 *     tags={"Bill"},
 *     summary="Bill for subscription",
 *     operationId="Bill for subscription",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="subscription_id",
 *         in="query",
 *         description="Id of subscription",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Parameter(
 *         name="legal_id",
 *         in="query",
 *         description="Id of company (legal)",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class BillRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subscription_id' => [
                'required', 'integer', 
                Rule::exists('subscriptions', 'id')->where(function ($query) {
                    return $query->where('price', '>', 0);
                }),
            ],
            'legal_id' => [
                'required', 'integer', 
                function ($attribute, $value, $fail) {
                    try {
                        Auth::user()->getLegalInfo($value);
                    } catch (\Throwable $e) {
                        $fail('Компания не существует.');  
                    }
                },
            ],
        ];
    }
}