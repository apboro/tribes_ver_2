<?php

namespace App\Http\ApiRequests\Community;


use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Str;

/**
 * @OA\Get(
 *  path="/api/v3/user/chats",
 *  operationId="community-filter",
 *  summary="Filter communities of auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Chats"},
 *     @OA\Parameter(
 *         name="offset",
 *         in="query",
 *         description="Begin records from number {offset}",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="Total records to display",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="rules_uuids[]",
 *         in="query",
 *         description="Filter by rule_uuids",
 *         required=false,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(type="string"),
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="telegram_id",
 *         in="query",
 *         description="Telegram id of Spodial User",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Community name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="tags_names[]",
 *         in="query",
 *         description="Community tags names",
 *         required=false,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(type="string"),
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="date_from",
 *         in="query",
 *         description="Communities with date of add to Spodial from",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="date_to",
 *         in="query",
 *         description="Communities with date of add to Spodial to",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *      @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiCommunityFilterRequest extends ApiRequest

{
    public function rules(): array
    {
        return [
            'name' => 'string',
            'tags_names' => 'array',
            'date_from' => 'date_format:U',
            'date_to' => 'date_format:U',
            'rules_uuids' =>'array',
            'rules_uuids.*' =>'string',
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.date_format' => $this->localizeValidation('date.incorrect_format'),
            'date_to.date_format' => $this->localizeValidation('date.incorrect_format')
        ];
    }

    public function prepareForValidation(): void
    {
        if (isset($this->tags_names[0]) && Str::contains($this->tags_names[0], ',')){
        $this->merge([
            'tags_names' => explode(',', $this->tags_names[0])
        ]);
    }
    }

}
