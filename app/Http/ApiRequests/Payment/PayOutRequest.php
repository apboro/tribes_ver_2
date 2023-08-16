<?php

namespace App\Http\ApiRequests\Payment;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Get(path="/api/v3/payout",
 *     tags={"Payout"},
 *     summary="Payout",
 *     operationId="Payout",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="CardId",
 *         in="query",
 *         description="Cards number from Tinkoff",
 *         required=true,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class PayOutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cardId' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'cardId.required' => 'Номер карты обязателен для заполнения'
        ];
    }
}
