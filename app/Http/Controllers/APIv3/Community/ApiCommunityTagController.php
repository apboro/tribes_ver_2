<?php

namespace App\Http\Controllers\APIv3\Community;

use App\Http\ApiRequests\Community\ApiAttachTagToCommunityRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Models\Tag;
use Illuminate\Support\Facades\Auth;

class ApiCommunityTagController extends Controller
{
    public function attachTagToChat(ApiAttachTagToCommunityRequest $request): ApiResponse
    {
        $user = Auth::user();
        $tags = null;
        foreach ($request->input('tags') as $input_tag) {
            $tags[] = Tag::firstOrCreate(['name' => $input_tag, 'user_id' => $user->id]);
        }

        $community = Community::owned()->where('id', $request->input('community_id'))->first();

        if ($community)
        {
            $community->tags()->sync([]);
            if ($tags) {
                foreach ($tags as $tag) {
                    $community->tags()->attach($tag);
                }
            }
            $this->removeUnusedTags();

            return ApiResponse::success('common.community_tag_attach_success');
        }

        return ApiResponse::error('common.not_found');
    }

    public function removeUnusedTags()
    {
        $unusedTags = Tag::doesntHave('communities')->get();
        foreach ($unusedTags as $tag) {
            $tag->delete();
        }
    }

}
