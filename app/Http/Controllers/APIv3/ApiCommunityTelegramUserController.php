<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiCommunityUserListAddRequest;
use App\Http\ApiRequests\ApiCommunityUserListRemoveRequest;
use App\Http\ApiRequests\Community\ApiCommunityTelegramUserDetachAllRequest;
use App\Http\ApiRequests\Community\ApiCommunityTelegramUserDetachRequest;
use App\Http\ApiRequests\Community\ApiTelegramUserFilterRequest;
use App\Http\ApiResources\ApiCommunityTelegramUserCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\TelegramUser;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiCommunityTelegramUserController extends Controller
{

    private TelegramMainBotService $telegramMainBotService;
    private TelegramUserListsRepositry $telegramUserListsRepositry;

    public function __construct(
        TelegramMainBotService     $telegramMainBotService,
        TelegramUserListsRepositry $telegramUserListsRepositry
    )
    {

        $this->telegramMainBotService = $telegramMainBotService;
        $this->telegramUserListsRepositry = $telegramUserListsRepositry;
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

        foreach ($communities as $community) {
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

        $query = TelegramUser::with(['communities', 'userList'])
            ->whereHas('communities', function ($query) {
                $query->where('owner', Auth::user()->id)
                    ->whereNull('telegram_users_community.exit_date')
                    ->orWhere('telegram_users_community.status', 'banned')
                    ->where('is_active', true)
                ;
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

        if (
            $request->boolean('banned') ||
            $request->boolean('muted') ||
            $request->boolean('whitelisted') ||
            $request->boolean('blacklisted')
        ) {
            $arr_to_search = [
                $request->boolean('banned') ? TelegramUserListsRepositry::TYPE_BAN_LIST : 0,
                $request->boolean('muted') ? TelegramUserListsRepositry::TYPE_MUTE_LIST : 0,
                $request->boolean('whitelisted') ? TelegramUserListsRepositry::TYPE_WHITE_LIST : 0,
                $request->boolean('blacklisted') ? TelegramUserListsRepositry::TYPE_BLACK_LIST : 0,
            ];
            $query->whereHas('userList', function ($query) use ($request, $arr_to_search) {
                $query->whereIn('type', $arr_to_search);
                if (!empty($request->input('community_id'))) {
                    $query->where('community_id', '=', $request->input('community_id'));
                }
            });
        }

        $count = $query->count();
        $telegram_users = $query->skip($request->offset)->take($request->limit)->orderBy('id')->get();

        return ApiResponse::listPagination(['Access-Control-Expose-Headers'=>'Items-Count', 'Items-Count'=>$count])->items(new ApiCommunityTelegramUserCollection($telegram_users));

    }

    public function addToList(ApiCommunityUserListAddRequest $request)
    {
        $result = false;
        if ($request->boolean('banned')) {
            $result = $this->telegramUserListsRepositry->add($request, $request->telegram_id);
        }

        if ($request->boolean('muted')) {
            $result = $this->telegramUserListsRepositry->add($request, $request->telegram_id, TelegramUserListsRepositry::TYPE_MUTE_LIST);
        }

        if ($request->boolean('whitelisted')) {
            $result = $this->telegramUserListsRepositry->add($request, $request->telegram_id, TelegramUserListsRepositry::TYPE_WHITE_LIST);
        }

        if ($request->boolean('kick')) {
            $this->telegramUserListsRepositry->kick($request, $request->telegram_id);
            return ApiResponse::success('Пользователь исключен');
        }

        if ($result) {
            return ApiResponse::success('Пользователь добавлен в список');
        } else {
            return ApiResponse::error('Пользователь в белом списке');
        }
    }

    public function removeFromList(ApiCommunityUserListRemoveRequest $request)
    {
        $result = false;
        if ($request->boolean('banned')) {
            $result = $this->telegramUserListsRepositry->remove($request, $request->telegram_id, TelegramUserListsRepositry::TYPE_BAN_LIST);
        }

        if ($request->boolean('muted')) {
            $result = $this->telegramUserListsRepositry->remove($request, $request->telegram_id, TelegramUserListsRepositry::TYPE_MUTE_LIST);
        }

        if ($request->boolean('whitelisted')) {
            $result = $this->telegramUserListsRepositry->remove($request, $request->telegram_id, TelegramUserListsRepositry::TYPE_WHITE_LIST);
        }

        if ($result){
            return ApiResponse::success('common.success');
        } else {
            return ApiResponse::error('common.not_found');
        }
    }
}
