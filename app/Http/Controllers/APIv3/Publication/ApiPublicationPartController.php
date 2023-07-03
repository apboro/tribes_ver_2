<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Http\ApiRequests\Publication\ApiPublicationPartDeleteRequest;
use App\Http\ApiRequests\Publication\ApiPublicationPartStoreRequest;
use App\Http\ApiResources\Publication\PublicationPartResourse;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\PublicationPart;
use App\Models\User;
use App\Repositories\Publication\PublicationPartRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
     * Remove the specified resource from storage.
     *
     * @param ApiPublicationPartDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiPublicationPartDeleteRequest $request, int $id): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->author == null) {
            ApiResponse::notFound('common.not_found');
        }
        $author_id = $user->author->id;
        $publication_part = PublicationPart::with('publication')->whereHas('publication', function ($query) use ($author_id) {
            $query->where('author_id', $author_id);
        })->where('id', $id);
        if ($publication_part == null) {
            ApiResponse::notFound('common.not_found');
        }
        $publication_part->delete();
        return ApiResponse::success();
    }
}
