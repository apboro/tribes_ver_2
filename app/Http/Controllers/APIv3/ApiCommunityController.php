<?php

namespace App\Http\Controllers\APIv3;

use App\Events\CreateCommunity;
use App\Http\ApiRequests\ApiCommunityAddRequest;
use App\Http\ApiRequests\ApiShowCommunityRequest;
use App\Http\ApiResources\CommunitiesCollection;
use App\Http\ApiResources\CommunityResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Community\CommunityListRequest;
use App\Models\Community;
use App\Models\TelegramConnection;

use App\Repositories\Community\CommunityRepositoryContract;

use App\Repositories\Tariff\TariffRepositoryContract;
use App\Services\Telegram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
class ApiCommunityController extends Controller
{
    public function __construct(
        CommunityRepositoryContract $communityRepo,
        TariffRepositoryContract $tariffRepository
    ) {
        $this->communityRepo = $communityRepo;
        $this->tariffRepository = $tariffRepository;
    }

    public function index(CommunityListRequest $request):ApiResponse
    {
        $communities = $this->communityRepo->getList($request);
        return ApiResponse::common([
            'data'=>new CommunitiesCollection($communities),
        ]);
    }

    public function show(ApiShowCommunityRequest $request,$id):ApiResponse
    {
        $community = Community::find($id);
        if (empty($community)) {
            return ApiResponse::notFound('validation.community.not_found');
        }
        if(!Auth::user()->can('view',$community)){
            return ApiResponse::unauthorized();
        }
        return ApiResponse::common(['community' => new CommunityResource($community)]);
    }

    public function store(ApiCommunityAddRequest $request):ApiResponse
    {
        $telegam_connection = TelegramConnection::where('hash','=',$request->input('hash'))->first();
        if(empty($telegam_connection)){
            return ApiResponse::notFound('validation.hash_not_found');
        }
        if($telegam_connection->status !== 'connected'){
            return ApiResponse::error('validation.hash_telegram_not_respond');
        }

        $community = $this->communityRepo->create($telegam_connection);
        $community->tariff()->create($this->tariffRepository->createTarif($community));

        $community->statistic()->create([
            'community_id' => $community->id
        ]);

        Event::dispatch(new CreateCommunity($community));

        $community->generateHash();
        $community->save();

        $telegam_connection->status = 'completed';
        $telegam_connection->save();

        return ApiResponse::common(['community' => new CommunityResource($community)]);
    }


}
