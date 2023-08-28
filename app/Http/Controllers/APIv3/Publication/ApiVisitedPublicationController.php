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
use Illuminate\Support\Facades\DB;

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

        $innerSql = 'SELECT publication_id, max(last_visited) as lv
                    FROM "visited_publications"
                    WHERE "user_id" = ' . $user->id . '
                    group BY publication_id
                    ORDER BY lv DESC';

        $publicationSql = 'select * 
                    from (' . $innerSql . ') as t
                    inner join "publications" on "t"."publication_id" = "publications"."id"
                    order by lv DESC ';

        $offset = (int)$request->offset ?? 0;
        $limit = (int)$request->limit ?? 3;

        $publications = DB::select($publicationSql . ' limit ' . $limit . ' offset ' . $offset);
        $count = DB::select('select count(*) as count from (' . $innerSql . ') as t')[0]->count;

        return ApiResponse::listPagination(
            [
                'Access-Control-Expose-Headers' => 'Items-Count',
                'Items-Count' => $count
            ]
        )->items((new PublicationCollection($publications))->toArray($request));
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
