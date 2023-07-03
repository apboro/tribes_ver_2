<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Http\ApiRequests\Publication\ApiPublicationDeleteRequest;
use App\Http\ApiRequests\Publication\ApiPublicationListRequest;
use App\Http\ApiRequests\Publication\ApiPublicationShowForAllRequest;
use App\Http\ApiRequests\Publication\ApiPublicationShowRequest;
use App\Http\ApiRequests\Publication\ApiPublicationStoreRequest;
use App\Http\ApiRequests\Publication\ApiPublicationUpdateRequest;
use App\Http\ApiResources\Publication\PublicationResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\User;
use App\Repositories\Publication\PublicationRepository;
use Illuminate\Support\Facades\Auth;

class ApiPublicationController extends Controller
{
    private PublicationRepository $publicationRepository;

    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param ApiPublicationListRequest $request
     * @return ApiResponse
     */
    public function list(ApiPublicationListRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->author == null) {
            return ApiResponse::notFound('common.not_found');
        }
        $publication = Publication::where('author_id', $user->author->id)->get();
        return ApiResponse::common(PublicationResource::collection($publication)->toArray($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApiPublicationStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiPublicationStoreRequest $request): ApiResponse
    {
        $publication = $this->publicationRepository->store($request);
        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * Display the specified resource.
     *
     * @param ApiPublicationShowRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiPublicationShowRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->author == null) {
            ApiResponse::notFound('common.not_found');
        }
        $publication = Publication::where('id', $id)->where('author_id', $user->author->id)->first();
        if ($publication === null) {
            return ApiResponse::notFound('not_found');
        }
        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiPublicationUpdateRequest $request
     * @return ApiResponse
     */
    public function update(ApiPublicationUpdateRequest $request, int $id): ApiResponse
    {
        $publication = $this->publicationRepository->update($request, $id);
        if ($publication === null) {
            ApiResponse::notFound('common.not_found');
        }
        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ApiPublicationDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiPublicationDeleteRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->author == null) {
            ApiResponse::notFound('common.not_found');
        }
        $publication = Publication::where('id', $id)->where('author_id', $user->author->id)->first();
        if ($publication == null) {
            ApiResponse::notFound('common.not_found');
        }
        $publication->delete();
        return ApiResponse::success();
    }

    public function showByUuid(ApiPublicationShowForAllRequest $request, string $uuid)
    {
        $publication = Publication::where('uuid', $uuid)->first();
        if ($publication == null) {
            ApiResponse::notFound('common.not_found');
        }
        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }
}
