<?php

namespace App\Http\Controllers\APIv3;

use App\Events\CreateCommunity;
use App\Http\ApiRequests\ApiCommunityAddRequest;
use App\Http\ApiRequests\ApiCommunityFilterRequest;
use App\Http\ApiRequests\ApiCommunityListRequest;
use App\Http\ApiRequests\ApiShowCommunityRequest;
use App\Http\ApiResources\CommunitiesCollection;
use App\Http\ApiResources\CommunityResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\TelegramConnection;
use App\Models\User;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Tariff\TariffRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class ApiCommunityController extends Controller
{
    private CommunityRepositoryContract $communityRepo;
    private TariffRepositoryContract $tariffRepository;

    public function __construct(CommunityRepositoryContract $communityRepo, TariffRepositoryContract $tariffRepository)
    {
        $this->communityRepo = $communityRepo;
        $this->tariffRepository = $tariffRepository;
    }

    /**
     * Communities list.
     *
     * TODO swagger annotations
     *
     * @param ApiCommunityListRequest $request
     *
     * @return ApiResponse
     */
    public function list(ApiCommunityListRequest $request): ApiResponse
    {
        $communities = $this->communityRepo->getList($request);

        return ApiResponse::list()
            ->items(CommunitiesCollection::make($communities)->toArray($request));
    }

    /**
     * Show community info.
     *
     * TODO swagger annotation
     *
     * @param ApiShowCommunityRequest $request
     * @param $id
     *
     * @return ApiResponse
     */
    public function show(ApiShowCommunityRequest $request, $id): ApiResponse
    {
        $community = Community::query()->with(['tags'])->find($id);
        /** @var User $user */
        $user = Auth::user();

        if (empty($community)) {
            return ApiResponse::notFound('validation.community.not_found');
        }

        if (!$user->can('view', $community)) {
            return ApiResponse::unauthorized();
        }
        return ApiResponse::common(CommunityResource::make($community)->toArray($request));
    }

    /**
     * Create community.
     *
     * @param ApiCommunityAddRequest $request
     *
     * @return ApiResponse
     */
    public function store(ApiCommunityAddRequest $request): ApiResponse
    {
        $telegram_connection = TelegramConnection::query()->where('hash', '=', $request->input('hash'))->first();

        if (empty($telegram_connection)) {
            return ApiResponse::notFound('validation.hash_not_found');
        }

        if ($telegram_connection->status !== 'connected') {
            return ApiResponse::error('validation.hash_telegram_not_respond');
        }

        $community = $this->communityRepo->create($telegram_connection);

        $community->tariff()->create($this->tariffRepository->createTarif($community));

        $community->statistic()->create([
            'community_id' => $community->id,
        ]);

        Event::dispatch(new CreateCommunity($community));

        $community->generateHash();
        $community->save();

        $telegram_connection->status = 'completed';
        $telegram_connection->save();

        return ApiResponse::common(CommunityResource::make($community)->toArray($request));
    }

    public function filter(ApiCommunityFilterRequest $request):ApiResponse
    {

        $communities = $this->communityRepo->getList($request);

        return ApiResponse::list()
            ->items(CommunitiesCollection::make($communities)->toArray($request));
    }
}
