<?php

namespace App\Http\ApiRequests\Course;

use App\Http\ApiRequests\ApiRequest;
use Carbon\Carbon;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *  path="/api/v3/courses/{id}",
 *  operationId="coourse-update",
 *  summary="Update course by id",
 *  security={{"sanctum": {} }},
 *  tags={"Courses"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of course in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="title", type="string", example="test"),
 *                 @OA\Property(property="cost", type="integer", example="100"),
 *                 @OA\Property(property="access_days", type="integer", example="100"),
 *                 @OA\Property(property="is_published", type="boolean", example=false),
 *                 @OA\Property(property="is_active", type="boolean", example=false),
 *                 @OA\Property(property="is_ethernal", type="boolean", example=false),
 *                 @OA\Property(property="payment_title", type="string", example="test"),
 *                 @OA\Property(property="payment_description", type="string", example="test"),
 *                 @OA\Property(property="preview", type="integer", example="1"),
 *                 @OA\Property(property="thanks_text", type="string", example="test"),
 *                 @OA\Property(property="activation_date", type="date", example="2022-01-01"),
 *                 @OA\Property(property="deactivation_date", type="date", example="2024-01-01"),
 *                 @OA\Property(property="publication_date", type="date", example="2022-01-01"),
 *                 @OA\Property(property="shipping_noty", type="boolean", example="false"),
 *
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK",@OA\JsonContent())
 *)
 */

class ApiCourseUpdateRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }


    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
            'title' => 'string',
            'cost' => 'integer',
            'access_days' => 'integer',
            'is_published' => 'boolean',
            'is_active' => 'boolean',
            'is_ethernal' => 'boolean',
            'payment_title' => 'string',
            'payment_description' => 'string',
            'preview' => 'integer',
            'thanks_text' => 'string',
            'shipping_noty' => 'boolean',
        ];
    }

    public function prepareForValidation():void
    {
        $this->request->set('activation_date', (
            $this->request->get('is_active') ? Carbon::now() : $this->request->get('activation_date'))
        );
        $this->request->set('activation_date', (
            $this->request->get('is_ethernal') ? null : $this->request->get('deactivation_date'))
        );
        $this->request->set('publication_date', (
            $this->request->get('is_published') ? Carbon::now() : $this->request->get('publication_date'))
        );
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('course.id_required'),
            'id.integer' => $this->localizeValidation('course.id_integer'),
        ];
    }
}
