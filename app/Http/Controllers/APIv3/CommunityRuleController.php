<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiCommunityRuleEditRequest;
use App\Http\ApiRequests\ApiCommunityRuleListRequest;
use App\Http\ApiRequests\ApiCommunityRuleShowRequest;
use App\Http\ApiRequests\ApiCommunityRuleStoreRequest;
use App\Http\ApiResources\ApiCommunityRuleCollection;
use App\Http\ApiResources\ApiCommunityRuleResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\CommunityRule;
use App\Repositories\Community\CommunityRuleRepository;
use Illuminate\Support\Facades\Auth;

class CommunityRuleController extends Controller
{

    private CommunityRuleRepository $communityRuleRepository;

    /**
     * @param CommunityRuleRepository $communityRuleRepository
     */

    public function __construct(CommunityRuleRepository $communityRuleRepository)
    {

        $this->communityRuleRepository = $communityRuleRepository;
    }

    /**
     *
     * @param ApiCommunityRuleListRequest $request
     * @return ApiResponse
     */
    public function list(ApiCommunityRuleListRequest $request): ApiResponse
    {
        $community_rules = CommunityRule::where('user_id', Auth::user()->id)->paginate(20);
        return ApiResponse::list()->items(ApiCommunityRuleCollection::make($community_rules)->toArray($request));
    }

    /**
     *
     * @param ApiCommunityRuleStoreRequest $request
     * @return ApiResponse
     */

    public function store(ApiCommunityRuleStoreRequest $request): ApiResponse
    {
        /** @var CommunityRule $community_rule */
        $community_rule = $this->communityRuleRepository->add($request);
        if ($community_rule === null) {
            return ApiResponse::error(trans('responses/common.add_error'));
        }
        return ApiResponse::common(
            ApiCommunityRuleResource::make($community_rule)->toArray($request)
        );
    }

    /**
     *
     * @param ApiCommunityRuleShowRequest $request
     * @param int $id
     * @return ApiResponse
     */

    public function show(ApiCommunityRuleShowRequest $request, int $id): ApiResponse
    {
        /** @var CommunityRule $community_rule */
        $community_rule = CommunityRule::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if ($community_rule === null) {
            return ApiResponse::error(trans('responses/common.add_error'));
        }
        return ApiResponse::common(ApiCommunityRuleResource::make($community_rule)->toArray($request));
    }


    /**
     *
     * @param ApiCommunityRuleEditRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function update(ApiCommunityRuleEditRequest $request, int $id): ApiResponse
    {
        /** @var CommunityRule $community_rule */
        $community_rule = CommunityRule::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if ($community_rule == null) {
            return ApiResponse::notFound(trans('responses/common.not_found'));
        }
        $community_rule = $this->communityRuleRepository->updateCommunityRule($community_rule, $request);
        return ApiResponse::common(
            ApiCommunityRuleResource::make($community_rule)->toArray($request)
        );
    }


}
