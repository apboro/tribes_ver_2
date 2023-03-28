<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\Community\ApiCommunityTelegramUserDetachAllRequest;
use App\Http\ApiRequests\Community\ApiCommunityTelegramUserDetachRequest;
use App\Http\ApiRequests\Community\ApiTelegramUserFilterRequest;
use App\Http\ApiResources\ApiCommunityTelegramUserCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\TelegramUser;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiCommunityTelegramUserController extends Controller
{

    private TelegramMainBotService $telegramMainBotService;

    public function __construct(TelegramMainBotService $telegramMainBotService)
    {

        $this->telegramMainBotService = $telegramMainBotService;
    }

    /**
     * @param ApiCommunityTelegramUserDetachRequest $request
     * @return ApiResponse
     */
    public function detachUser(ApiCommunityTelegramUserDetachRequest $request): ApiResponse
    {
        /** @var TelegramUser $telegram_user */
        $telegram_user = TelegramUser::where('telegram_id', '=', $request->input('telegram_id'))->first();

        /** @var Community $community */
        $community = Community::where('id', '=', $request->input('community_id'))->first();

        $telegram_user->communities()->detach($community);
        $this->telegramMainBotService->kickUser(
            config('telegram_bot.bot.botName'),
            $telegram_user->telegram_id,
            $community->connection->chat_id
        );
        return ApiResponse::success();
    }

    /**
     * @param ApiCommunityTelegramUserDetachAllRequest $request
     * @return ApiResponse
     */

    public function detachFromAllCommunities(ApiCommunityTelegramUserDetachAllRequest $request): ApiResponse
    {
        /** @var TelegramUser $telegram_user */
        $telegram_user = TelegramUser::where('telegram_id', '=', $request->input('telegram_id'))->first();

        /** @var Community $community */
        $communities = Community::with(['followers'])->whereHas('followers', function ($query) use ($request) {
            $query->where('telegram_id', '=', $request->input('telegram_id'));
        })->where('owner', '=', Auth::user()->id)->get();

        foreach($communities as $community){
            $this->telegramMainBotService->kickUser(
                config('telegram_bot.bot.botName'),
                $telegram_user->telegram_id,
                $community->connection->chat_id
            );
        }

        $telegram_user->communities()->detach($communities);

        return ApiResponse::success();
    }

    /**
     * @param ApiTelegramUserFilterRequest $request
     * @return ApiResponse
     */

    public function filter(ApiTelegramUserFilterRequest $request): ApiResponse
    {

        $query = TelegramUser::with(['communities','userList'])
            ->whereHas('communities', function ($query) {
                $query->where('owner', Auth::user()->id);
            })
            ->newQuery();
        if (!empty($request->input('accession_date_from'))) {
            $query->whereHas('communities', function ($query) use ($request) {
                $query->where('telegram_users_community.accession_date', '>=', strtotime($request->input('accession_date_from')));

            });
        }

        if (!empty($request->input('accession_date_to'))) {
            $query->whereHas('communities', function ($query) use ($request) {
                $query->where('telegram_users_community.accession_date', '<=', strtotime($request->input('accession_date_to')));

            });
        }

        if (!empty($request->input('community_id'))) {
            $query->whereHas('communities', function ($query) use ($request) {
                $query->where('telegram_users_community.community_id', '=', $request->input('community_id'));

            });
        }

        if (!empty($request->input('user_name'))) {
            $query->where(function ($query) use ($request) {
                $query->where('user_name', 'ilike', '%' . $request->input('user_name') . '%');
            });
        }

        if (!empty($request->input('name'))) {
            $query->where(function ($query) use ($request) {
                $query->where('first_name', 'ilike', '%' . $request->input('name') . '%')
                    ->orWhere('last_name', 'ilike', '%' . $request->input('name') . '%')
                    ->orWhere(DB::raw("CONCAT('first_name', ' ', 'last_name')"), 'ilike', "%" . $request->input('name') . "%");

            });
        }

        $telegram_users = $query->paginate(20);

        return ApiResponse::listPagination()->items(new ApiCommunityTelegramUserCollection($telegram_users));

    }
}
