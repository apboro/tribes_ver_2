<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\CommunityFilter;
use App\Http\Requests\API\CommunityRequest;
use App\Http\Resources\Manager\CommunityResource;
use App\Models\Community;
use App\Models\User;
use App\Repositories\Community\CommunityRepository;
use App\Services\File\FileSendService;
use Illuminate\Http\Request;

class AdminCommunityController extends Controller
{
    private CommunityRepository $communityRepository;

    private FilePrepareService $filePrepareService;

    public function __construct(
        CommunityRepository $communityRepository,
        FileSendService $fileSendService
    )
    {
        $this->communityRepository = $communityRepository;
        $this->fileSendService = $fileSendService;
    }

    public function list(Request $request, CommunityFilter $filter)
    {
        $communities =  Community::with('communityOwner', 'connection')->withCount('followers')->filter($filter)->paginate(request('filter.entries'), ['*'], 'filter.page');
        return CommunityResource::collection($communities);
    }

    public function get(CommunityRequest $request)
    {
        $community = $this->communityRepository->getCommunityById($request->id);

        return new CommunityResource($community);
    }

    public function export(Request $request, CommunityFilter $filter)
    {
        $names = [
            [
                'title' => 'id',
                'attribute' => 'id',
            ],
            [
                'title' => 'Название',
                'attribute' => 'title',
            ],
            [
                'title' => 'Владелец',
                'attribute' => 'owner_name',
            ],
            [
                'title' => 'Telegram',
                'attribute' => 'telegram',
            ],
            [
                'title' => 'Дата подключения',
                'attribute' => 'created_at',
            ],
            [
                'title' => 'Кол-во подписчиков',
                'attribute' => 'followers',
            ],
            [
                'title' => 'Сумма поступлений',
                'attribute' => 'balance',
            ],
        ];
        return $this->fileSendService->sendFile(
            Community::with('communityOwner', 'connection')->withCount('followers'),
            $names,
            CommunityResource::class,
            $request->get('type','csv'),
            'communities'
        );
    }
}
