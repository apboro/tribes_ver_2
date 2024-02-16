<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Publication;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/publication/{uuid}",
 *  operationId="publication-show-by-uuid",
 *  summary="Show publication by uuid",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *     @OA\Parameter(name="uuid",in="path",
 *         description="Uuid of publication part in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationShowForAllRequest extends ApiRequest
{

    public function all($keys = null)
    {
        $data = parent::all();
        $data['uuid'] = $this->route('uuid');

        return $data;
    }

    public function rules(): array
    {
        return [
            'uuid' => [
                'required',
                'uuid',
                function ($attribute, $value, $fail) {
                    $publication = Publication::findByUUID($value);
                    if (!$publication ||
                        ($publication->price > 0 && (!$this->user('sanctum') || $publication->isPublicationBuyed($this->user('sanctum')->id) === false))) {
                            $fail('Публикация ' . $value . ' не найдена.');
                    }
                },
            ],
        ];
    }
}
