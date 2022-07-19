<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CommunityRequest;
use App\Http\Resources\Manager\CommunityResource;
use App\Repositories\Community\CommunityRepository;

class CommunityController extends Controller
{
    private CommunityRepository $communityRepository;

    public function __construct(CommunityRepository $communityRepository)
    {
        $this->communityRepository = $communityRepository;
    }

    public function get(CommunityRequest $request)
    {
        $community = $this->communityRepository->getCommunityById($request->id);

        return new CommunityResource($community);
    }
}
