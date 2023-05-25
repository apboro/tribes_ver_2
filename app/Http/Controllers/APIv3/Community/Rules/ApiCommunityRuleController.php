<?php

namespace App\Http\Controllers\APIv3\Community\Rules;

use App\Http\ApiRequests\ApiCommunityRuleDeleteRequest;
use App\Http\ApiRequests\Moderation\ApiCommunityRuleEditRequest;
use App\Http\ApiRequests\Moderation\ApiCommunityRuleListRequest;
use App\Http\ApiRequests\Moderation\ApiCommunityRuleShowRequest;
use App\Http\ApiRequests\Moderation\ApiCommunityRuleStoreRequest;
use App\Http\ApiResources\Rules\ApiCommunityRuleCollection;
use App\Http\ApiResources\Rules\ApiCommunityRuleResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Jobs\BehaviorIncomeRuleJob;
use App\Models\CommunityRule;
use App\Repositories\Community\CommunityRuleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiCommunityRuleController extends Controller
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

        if($request->input('content')) {
            $communitiesList = $request->input('community_ids', []);
            log::info(json_encode($communitiesList, JSON_UNESCAPED_UNICODE));
            foreach($communitiesList as $communityId){
                BehaviorIncomeRuleJob::dispatch($community_rule, $communityId);
            }
        }

        return ApiResponse::common(
            ApiCommunityRuleResource::make($community_rule)->toArray($request)
        );
    }

    /**
     *
     * @param ApiCommunityRuleShowRequest $request
     * @param int $uuid
     * @return ApiResponse
     */

    public function show(ApiCommunityRuleShowRequest $request, string $uuid): ApiResponse
    {
        /** @var CommunityRule $community_rule */
        $community_rule = CommunityRule::where('uuid', $uuid)->where('user_id', Auth::user()->id)->first();
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
    public function update(ApiCommunityRuleEditRequest $request, string $uuid): ApiResponse
    {
        Log::info('update api comunication ');

        /** @var CommunityRule $community_rule */
        $community_rule = CommunityRule::where('uuid', $uuid)->where('user_id', Auth::user()->id)->first();
        if ($community_rule == null) {
            return ApiResponse::notFound(trans('responses/common.not_found'));
        }
        $community_rule = $this->communityRuleRepository->updateCommunityRule($community_rule, $request);
        return ApiResponse::common(
            ApiCommunityRuleResource::make($community_rule)->toArray($request)
        );
    }

    public function delete(ApiCommunityRuleDeleteRequest $request)
    {
        $moderation_rule = CommunityRule::where('user_id', Auth::user()->id)->where('uuid', $request->moderation_uuid)->first();
        if ($moderation_rule){
            $moderation_rule->delete();
            return ApiResponse::success('common.deleted');
        }
        return ApiResponse::error('common.not_found');
    }


}
