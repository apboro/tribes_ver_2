<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\Community\ApiTagDeleteRequest;
use App\Http\ApiRequests\Community\ApiTagShowListRequest;
use App\Http\ApiRequests\Community\ApiTagShowRequest;
use App\Http\ApiRequests\Community\ApiTagStoreRequest;
use App\Http\ApiResources\ApiTagCollection;
use App\Http\ApiResources\ApiTagResourse;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiTagController extends Controller
{

    /**
     * @param ApiTagShowListRequest $request
     * @return ApiResponse
     */

    public function index(ApiTagShowListRequest $request):ApiResponse
    {
        $user = Auth::user();
        $tag = Tag::where('user_id','=',$user->id)->get();
        return ApiResponse::list()->items(ApiTagCollection::make($tag)->toArray($request));
    }


    /**
     * @param ApiTagShowRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiTagShowRequest $request, int $id):ApiResponse
    {

        /** @var User $user */
        $user = Auth::user();
        $tag = Tag::where('id','=',$id)->where('user_id','=',$user->id)->first();
        return ApiResponse::common(ApiTagResourse::make($tag)->toArray($request));
    }

    /**
     * @param ApiTagDeleteRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function destroy(ApiTagDeleteRequest $request, int $id):ApiResponse
    {
        $user = Auth::user();
        $tag = Tag::where('id','=',$id)->where('user_id','=',$user->id)->first();
        if(!$tag->delete()){
            return ApiResponse::error('common.tag_delete_error');
        }
        return ApiResponse::success('common.tag_delete_success');
    }
}
