<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Http\ApiRequests\Publication\ApiPublicationPartDeleteRequest;
use App\Http\ApiRequests\Publication\ApiPublicationPartStoreRequest;
use App\Http\ApiResources\Publication\PublicationPartResourse;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\PublicationPart;
use App\Repositories\Publication\PublicationPartRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiPublicationPartController extends Controller
{
    private PublicationPartRepository $repository;

    /**
     * @param PublicationPartRepository $repository
     */
    public function __construct(PublicationPartRepository $repository)
    {
        $this->repository = $repository;
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
     * @param ApiPublicationPartStoreRequest $request
     * @return Response
     */
    public function store(ApiPublicationPartStoreRequest $request): ApiResponse
    {
        $publication_part = $this->repository->store($request);
        return ApiResponse::common(PublicationPartResourse::make($publication_part)->toArray($request));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PublicationPart $publicationPart
     * @return \Illuminate\Http\Response
     */
    public function show(PublicationPart $publicationPart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PublicationPart $publicationPart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicationPart $publicationPart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ApiPublicationPartDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiPublicationPartDeleteRequest $request, int $id): ApiResponse
    {
        PublicationPart::where('id', $id)->delete();
        return ApiResponse::success();
    }
}
