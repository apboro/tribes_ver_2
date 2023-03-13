<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiAttachTagToCommunityRequest;
use App\Http\ApiRequests\ApiDetachTagFromCommunityRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiCommunityTagController extends Controller
{
    public function attachTagToCommunity(ApiAttachTagToCommunityRequest $request):ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Tag $tag */
        $tag = Tag::where('id','=',$request->input('tag_id'))->
                    where('user_id','=',$user->id)->
                    first();
        /** @var Community $community */
        $community = Community::where('id','=',$request->input('community_id'))->first();
        $community->tags()->attach($tag);
        return ApiResponse::success('common.community_tag_attach_success');
    }

    public function detachTagFromCommunity(ApiDetachTagFromCommunityRequest $request){
        /** @var User $user */
        $user = Auth::user();

        /** @var Tag $tag */
        $tag = Tag::where('id','=',$request->input('tag_id'))->
                    where('user_id','=',$user->id)->
                    first();

        /** @var Community $community */
        $community = Community::where('id','=',$request->input('community_id'))->first();
        if(!$community->tags()->detach($tag)){
            return ApiResponse::error('common.community_tag_detach_error');
        }
        return ApiResponse::success('common.community_tag_detach_success');
    }
}
