<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Http\ApiRequests\Publication\ApiPublicationShowRequest;
use App\Http\ApiRequests\Publication\ApiPublicationStoreRequest;
use App\Http\ApiRequests\Publication\ApiPublicationUpdateRequest;
use App\Http\ApiResources\Publication\PublicationResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Repositories\Publication\PublicationRepository;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $publication = Publication::where('id', $id)->first();
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
        return ApiResponse::common(PublicationResource::make($publication)->toArray($request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Publication $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
