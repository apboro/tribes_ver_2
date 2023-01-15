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

    private FileSendService $fileSendService;

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
        $communities =  Community::with('communityOwner', 'connection')->filter($filter)->paginate(request('filter.entries'), ['*'], 'filter.page');
//        foreach ($communities->get() as $c) {
//            if ($c->connection->chat_invite_link === null && $c->connection->botStatus === 'administrator') {
//                $response = Http::get('https://api.telegram.org/bot'.env('TELEGRAM_BOT_TOKEN').'/createChatInviteLink?chat_id='.$c->connection->chat_id);
//                $c->connection->chat_invite_link = $response->json('result.invite_link');
//                $c->save();
//                sleep(1);
//            }
//        }
//        $communities = $communities->paginate($request->filter['entries']);
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
                'title' => 'Владелец',
                'attribute' => 'owner_name',
            ],
            [
                'title' => 'Название',
                'attribute' => 'title',
            ],
            [
                'title' => 'Дата регистрации',
                'attribute' => 'created_at',
            ],
            [
                'title' => 'Сумма поступлений',
                'attribute' => 'balance',
            ],
        ];
        return $this->fileSendService->sendFile(
            Community::with('communityOwner', 'connection')->filter($filter),
            $names,
            CommunityResource::class,
            $request->get('type','csv'),
            'communities'
        );
    }
}
