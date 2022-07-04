<?php

namespace App\Http\Controllers\API;

use App\Filters\API\CommunitiesFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommunitiesRequest;
use App\Http\Requests\API\CommunityRequest;
use App\Http\Resources\CommunitiesResource;
use App\Http\Resources\CommunityResource;
use App\Http\Resources\Knowledge\QuestionsResource;
use App\Repositories\Community\CommunityRepository;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    private CommunityRepository $communityRepository;

    public function __construct(CommunityRepository $communityRepository)
    {
        $this->communityRepository = $communityRepository;
    }

    public function list(CommunitiesRequest $request,CommunitiesFilter $filters)
    {
        $models = $this->communityRepository->getCommunitiesForOwner(\Auth::user()->id, $filters);
        return new CommunitiesResource($models);
    }

    public function get(CommunityRequest $request)
    {
        $model = $this->communityRepository->getCommunityById( $request->id );

        return new CommunityResource($model);
    }
}
