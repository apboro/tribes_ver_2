<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Http\ApiRequests\Publication\ApiVisitedPublicationListRequest;
use App\Http\ApiRequests\Publication\ApiVisitedPublicationStoreRequest;
use App\Http\ApiResources\Publication\PublicationCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\VisitedPublication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ApiVisitedPublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ApiVisitedPublicationListRequest $request
     * @return ApiResponse
     */

    public function list(ApiVisitedPublicationListRequest $request): ApiResponse
    {
        $user = Auth::user();
        $publications = Publication::with('visited')->whereRelation('visited', 'user_id', $user->id)
            ->join('visited_publications', 'visited_publications.publication_id', '=', 'publications.id')
            ->orderBy('visited_publications.last_visited', 'DESC');
        $count = $publications->count();
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((
        new PublicationCollection($publications->skip($request->offset ?? 0)->take($request->limit ?? 3)->get()
        ))->toArray($request));

    }

    public function store(ApiVisitedPublicationStoreRequest $request)
    {
        $user = Auth::user();
        VisitedPublication::updateOrCreate([
            'user_id' => $user->id,
            'publication_id' => $request->input('publication_id')
        ], ['last_visited' => Carbon::now()]);
        return ApiResponse::success();
    }

}
