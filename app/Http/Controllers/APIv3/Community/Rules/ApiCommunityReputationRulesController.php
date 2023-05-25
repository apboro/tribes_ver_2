<?php

namespace App\Http\Controllers\APIv3\Community\Rules;

use App\Http\ApiRequests\ApiDeleteReputationRuleRequest;
use App\Http\ApiRequests\Reputation\ApiCommunityReputationRuleEditRequest;
use App\Http\ApiRequests\Reputation\ApiCommunityReputationRuleListRequest;
use App\Http\ApiRequests\Reputation\ApiCommunityReputationRuleShowRequest;
use App\Http\ApiRequests\Reputation\ApiCommunityReputationRuleStoreRequest;
use App\Http\ApiRequests\Reputation\ApiCommunityReputationTemplateRequest;
use App\Http\ApiResources\ApiCommunityReputationRuleCollection;
use App\Http\ApiResources\ApiCommunityReputationRuleResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\CommunityReputationRules;
use App\Repositories\Community\CommunityReputationRepository;
use Illuminate\Support\Facades\Auth;

class ApiCommunityReputationRulesController extends Controller
{
    /**
     * @var CommunityReputationRepository
     */
    private CommunityReputationRepository $communityReputationRepository;

    public function __construct(CommunityReputationRepository $communityReputationRepository)
    {
        $this->communityReputationRepository = $communityReputationRepository;
    }


    public function getTemplate(ApiCommunityReputationTemplateRequest $request):ApiResponse
    {
        return ApiResponse::common([
            'keywords_up' => trans('responses/chat_reputation.keywords_up'),
            'keywords_down' => trans('responses/chat_reputation.keywords_down'),
            'notify_content_user' => trans('responses/chat_reputation.notify_content_user'),
            'text_public_rate_in_chat' => trans('responses/chat_reputation.text_public_rate_in_chat'),
            'ranks' => trans('responses/chat_reputation.ranks'),
            'notify_about_reset_reputation' => trans('responses/chat_reputation.notify_about_reset_reputation'),
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param ApiCommunityReputationRuleListRequest $request
     * @return ApiResponse
     */
    public function list(ApiCommunityReputationRuleListRequest $request):ApiResponse
    {
        /** @var CommunityReputationRules $community_reputation */
        $community_reputations = CommunityReputationRules::where('user_id',Auth::user()->id)->get();
        return ApiResponse::list()->items(ApiCommunityReputationRuleCollection::make($community_reputations)->toArray($request));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ApiCommunityReputationRuleStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiCommunityReputationRuleStoreRequest $request): ApiResponse
    {

        $community_reputation = $this->communityReputationRepository->add($request);
        if($community_reputation == null){
            return ApiResponse::error(trans('responses/common.add_error'));
        }
        return ApiResponse::common(
            ApiCommunityReputationRuleResource::make($community_reputation)->toArray($request)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param ApiCommunityReputationRuleShowRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiCommunityReputationRuleShowRequest $request, string $uuid):ApiResponse
    {
        /** @var CommunityReputationRules $community_reputation */
        $community_reputation = CommunityReputationRules::where('uuid',$uuid)->where('user_id',Auth::user()->id)->first();
        if($community_reputation === null){
            return ApiResponse::notFound(trans('responses/common.not_found'));
        }
        return ApiResponse::common(ApiCommunityReputationRuleResource::make($community_reputation)->toArray($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiCommunityReputationRuleEditRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function update(ApiCommunityReputationRuleEditRequest $request, string $uuid):ApiResponse
    {
        $community_reputation = $this->communityReputationRepository->edit($request,$uuid);
        if($community_reputation == null){
            return ApiResponse::error(trans('responses/common.add_error'));
        }
        return ApiResponse::common(
            ApiCommunityReputationRuleResource::make($community_reputation)->toArray($request)
        );
    }
    public function destroy(ApiDeleteReputationRuleRequest $request)
    {
        $ruleToDelete = CommunityReputationRules::findOrFail($request->reputation_rule_uuid);
        $ruleToDelete->delete();
        return ApiResponse::success('common.deleted');
    }

}
