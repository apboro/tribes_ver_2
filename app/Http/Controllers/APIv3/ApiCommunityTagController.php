<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\Community\ApiAttachTagToCommunityRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiCommunityTagController extends Controller
{
    public function attachTagToChat(ApiAttachTagToCommunityRequest $request):ApiResponse
    {
        $user = Auth::user();

        foreach ($request->input('tags') as $input_tag)
        {
            $tags[] = Tag::firstOrCreate(['name' => $input_tag, 'user_id' => $user->id]);
        }

        $community = Community::owned()->where('id',$request->input('community_id'))->first();

        if ($community) {
            $community->tags()->sync([]);
            foreach ($tags as $tag)
                $community->tags()->attach($tag);
            return ApiResponse::success('common.community_tag_attach_success');
        }

        return ApiResponse::error('common.not_found');

    }

}
