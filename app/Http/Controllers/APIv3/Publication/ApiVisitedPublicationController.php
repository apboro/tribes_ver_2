<?php

namespace App\Http\Controllers\APIv3\Publication;

use App\Http\ApiRequests\Publication\ApiVisitedPublicationListRequest;
use App\Http\ApiResources\Publication\PublicationCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Publication;
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
        $publications = Publication::with('visited')->whereRelation('visited', 'user_id', $user->id);
        $count = $publications->count();
        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ])->items((
        new PublicationCollection($publications->skip($request->offset ?? 0)->take($request->limit ?? 3)->orderBy('id')->get()
        ))->toArray($request));

    }

}
