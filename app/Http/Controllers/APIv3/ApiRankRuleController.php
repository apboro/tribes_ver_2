<?php


namespace App\Http\Controllers\APIv3;


use App\Http\ApiRequests\UserRank\ApiRankRuleListRequest;
use App\Http\ApiRequests\UserRank\ApiRankRuleShowRequest;
use App\Http\ApiRequests\UserRank\ApiRankRuleStoreRequest;
use App\Http\ApiRequests\UserRank\ApiRankRuleUpdateRequest;
use App\Http\ApiResources\ApiRankRuleResource;
use App\Http\ApiResources\ApiRankRuleCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Repositories\Rank\RankRuleRepository;

class ApiRankRuleController
{
    private RankRuleRepository $rankRuleRepository;

    public function __construct(RankRuleRepository $rankRuleRepository)
    {
        $this->rankRuleRepository = $rankRuleRepository;
    }

    /**
     * Display list of ranks
     */
    public function list(ApiRankRuleListRequest $request): ApiResponse
    {
        $rankRule = $this->rankRuleRepository->list();

        return ApiResponse::list()->items(ApiRankRuleCollection::make($rankRule)->toArray($request));
    }

    /**
     * Store new rank
     */
    public function store(ApiRankRuleStoreRequest $request): ApiResponse
    {
        $rankRule = $this->rankRuleRepository->add($request);

        if (!$rankRule) {
            return ApiResponse::error(trans('Не удалось добавить ранг'));
        }

        return ApiResponse::common(ApiRankRuleResource::make($rankRule)->toArray($request));
    }

    /**
     * Update the specified rank
     */
    public function update(ApiRankRuleUpdateRequest $request, int $id): ApiResponse
    {
        $rankRule = $this->rankRuleRepository->edit($request, $id);

        if (!$rankRule) {
            return ApiResponse::error('Не удалось обновить ранг');
        }

        return ApiResponse::common(ApiRankRuleResource::make($rankRule)->toArray($request));
    }

    /**
     * Display the specified rank
     */
    public function show(ApiRankRuleShowRequest $request, int $id): ApiResponse
    {
        $rankRule = $this->rankRuleRepository->show($id);

        if (!$rankRule) {
            return ApiResponse::error('Не удалось показать ранг');
        }

        return ApiResponse::common(ApiRankRuleResource::make($rankRule)->toArray($request));
    }
}