<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommunityRequest;
use App\Http\Resources\Manager\CommunityResource;
use App\Repositories\Community\CommunityRepository;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    private CommunityRepository $communityRepository;

    public function __construct(CommunityRepository $communityRepository)
    {
        $this->communityRepository = $communityRepository;
    }

    /**
     * @OA\Post(
     *     path="/v2/communities",
     *     tags={"CommunityController"},
     *     summary="Get list communities",
     *     operationId="getListCommunities",
     *     security={{"sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Successfuly get list communities",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  type="string",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     * )
     */

    public function list(Request $request)
    {
        $communities = $this->communityRepository->getAllCommunity();

        return CommunityResource::collection($communities);
    }

    /**
     * @OA\Post(
     *     path="/v2/community",
     *     tags={"CommunityController"},
     *     summary="Get community by id",
     *     operationId="getCommunityById",
     *     security={{"sanctum": {} }},
     *     @OA\RequestBody(
     *         required=false,
     *         description="Тело запроса для получения информации об одном сообщества",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfuly get list payments",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  type="string",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="The given data was invalid",
     *     ),
     * )
     */

    public function get(CommunityRequest $request)
    {
        $community = $this->communityRepository->getCommunityById($request->id);

        return new CommunityResource($community);
    }
}
