<?php

namespace App\Http\Controllers\API;

use App\Helper\ArrayHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TeleDialogStatRequest;
use App\Models\Community;
use Illuminate\Support\Facades\Auth;

abstract class StatController extends Controller
{
    /**
     * @param TeleDialogStatRequest $request
     * @return array
     */
    protected function getCommunityIds(TeleDialogStatRequest $request): array
    {
        $community_ids = $request->get('community_ids');
        if (!$community_ids)
        {
            $community_ids = 'all';
        }
        if ($community_ids == 'all') {
            $communityIds = ArrayHelper::getColumn(Community::where('owner', Auth::user()->id)->get(),'id');
        } else {
            $communityIds = explode('-', $community_ids);
            $communityIds = array_filter($communityIds);
            if (empty($communityIds)) {
                abort(403);
            }
        }
        return $communityIds;
    }
}