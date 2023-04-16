<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiCommunityReputationRuleListRequest;
use App\Http\ApiRequests\ApiCommunityReputationRuleShowRequest;
use App\Http\ApiRequests\ApiCommunityReputationRuleStoreRequest;
use App\Http\ApiResources\ApiCommunityReputationRuleCollection;
use App\Http\ApiResources\ApiCommunityReputationRuleResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\CommunityReputationRules;
use App\Repositories\Community\CommunityReputationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        if($community_reputation === null){
            return ApiResponse::error(trans('responses/common.add_error'));
        }
        return ApiResponse::common(ApiCommunityReputationRuleResource::make($community_reputation)->toArray($request));
    }

    /**
     * Display the specified resource.
     *
     * @param ApiCommunityReputationRuleShowRequest $request
     * @param int $id
     * @return ApiResponse
     */
    public function show(ApiCommunityReputationRuleShowRequest $request, int $id):ApiResponse
    {
        /** @var CommunityReputationRules $community_reputation */
        $community_reputation = CommunityReputationRules::where('id',$id)->where('user_id',Auth::user()->id)->first();
        if($community_reputation === null){
            return ApiResponse::notFound(trans('responses/common.not_found'));
        }
        return ApiResponse::common(ApiCommunityReputationRuleResource::make($community_reputation)->toArray($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
