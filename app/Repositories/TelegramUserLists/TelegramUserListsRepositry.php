<?php

namespace App\Repositories\TelegramUserLists;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Community;
use App\Models\TelegramUser;
use App\Models\TelegramUserCommunity;
use App\Models\TelegramUserList;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\Auth;

class TelegramUserListsRepositry
{
    protected TelegramMainBotService $telegramMainBotService;

    public function __construct(
        TelegramMainBotService $telegramMainBotService
    )
    {

        $this->telegramMainBotService = $telegramMainBotService;
    }

    const TYPE_WHITE_LIST = 2;
    const TYPE_MUTE_LIST = 3;
    const TYPE_BAN_LIST = 4;
    const SPAMMER = 1;

    public function add(ApiRequest $request, int $telegram_id, int $type = self::TYPE_BAN_LIST)
    {
        /** @var TelegramUserList $telegram_list */
        foreach ($request->input('community_ids') as $community_id) {

            $telegramUserAlreadyInList = TelegramUserList::query()
                ->where('telegram_id', '=', $telegram_id)
                ->where('community_id', '=', $community_id)
                ->first();

            if ($telegramUserAlreadyInList && $telegramUserAlreadyInList->type === self::TYPE_WHITE_LIST) {
                return false;
            }

            $telegram_list = TelegramUserList::updateOrCreate([
                'telegram_id' => $telegram_id,
                'community_id' => $community_id,
            ], ['type' => $type]);
        }

        if ($type === self::TYPE_BAN_LIST) {
            try {
                /** @var Community $community */
                $community = Community::where('id', $community_id)->first();
                $community_telegram_chat_id = $community->connection->chat_id;
                $this->telegramMainBotService->kickUser(
                    config('telegram_bot.bot.botName'),
                    $telegram_id,
                    $community_telegram_chat_id
                );
                $telegramUserCommunity = TelegramUserCommunity::where('telegram_user_id', $telegram_id)
                    ->where('community_id', $community->id)->first();
                 $telegramUserCommunity->exit_date = time();
                 $telegramUserCommunity->status = 'banned';
                 $telegramUserCommunity->save();
            } catch (\Exception $e) {
                TelegramLogService::staticSendLogMessage('Ban list error ' . $e);
            }
        }
        if ($type === self::TYPE_MUTE_LIST) {
            try {
                /** @var Community $community */
                $community = Community::where('id', $community_id)->first();
                $community_telegram_chat_id = $community->connection->chat_id;
                $this->telegramMainBotService->muteUser(
                    config('telegram_bot.bot.botName'),
                    $telegram_id,
                    $community_telegram_chat_id,
                    60
                );
                $telegramUserCommunity = TelegramUserCommunity::where('telegram_user_id', $telegram_id)
                    ->where('community_id', $community->id)->first();
                $telegramUserCommunity->status = 'muted';
                $telegramUserCommunity->save();
            } catch (\Exception $e) {
                TelegramLogService::staticSendLogMessage('Mute list error ' . $e);
            }
        }

        if ($request->input('is_spammer')) {
            $telegram_list->listParameters()->sync([self::SPAMMER]);
        }

        return true;
    }

    public
    function remove(ApiRequest $request, int $telegram_id, int $type = self::TYPE_BAN_LIST)
    {
        /** @var TelegramUserList $telegram_list */
        foreach ($request->input('community_ids') as $community_id) {
            $telegram_list = TelegramUserList::query()
                ->where('telegram_id', $telegram_id)
                ->where('community_id', $community_id)
                ->where('type', $type)
                ->first();

            if (!$telegram_list) {
                return false;
            }

            if ($request->input('is_spammer') === 0) {
                $telegram_list->listParameters()->sync([]);
            }

            if ($type === self::TYPE_BAN_LIST) {
                try {
                    /** @var Community $community */
                    $community = Community::where('id', $community_id)->first();
                    $community_telegram_chat_id = $community->connection->chat_id;
                    $this->telegramMainBotService->unKickUser(
                        config('telegram_bot.bot.botName'),
                        $telegram_id,
                        $community_telegram_chat_id
                    );
                    $telegramUserCommunity = TelegramUserCommunity::where('telegram_user_id', $telegram_id)
                        ->where('community_id', $community->id)->first();
                    $telegramUserCommunity->status = null;
                    $telegramUserCommunity->save();
                } catch (\Exception $e) {
                    TelegramLogService::staticSendLogMessage('Ban list error' . $e);
                }
            }
            $telegram_list->delete();
        }

        return true;
    }

    public
    function detach(ApiRequest $request, int $telegram_id): void
    {

        foreach ($request->input('community_ids') as $community_id) {
            /** @var TelegramUserList $telegram_list */
            TelegramUserList::query()
                ->where('telegram_id', '=', $telegram_id)
                ->where('community_id', '=', $community_id)
                ->delete();
        }
    }

    public function detachByCommunityId(int $communityId, int $telegramId): void
    {
        $community = Community::where('id', $communityId)->first();
        $community_telegram_chat_id = $community->connection->chat_id;
        $this->telegramMainBotService->kickUser(
            config('telegram_bot.bot.botName'),
            $telegramId,
            $community_telegram_chat_id
        );
        $this->telegramMainBotService->unKickUser(
            config('telegram_bot.bot.botName'),
            $telegramId,
            $community_telegram_chat_id
        );
        $telegramUserCommunity = TelegramUserCommunity::where('telegram_user_id', $telegramId)
            ->where('community_id', $community->id)->first();
        if ($telegramUserCommunity) {
            $telegramUserCommunity->exit_date = time();
            $telegramUserCommunity->status = 'kicked';
            $telegramUserCommunity->save();
        }
    }

    public
    function filter(ApiRequest $request, int $type = self::TYPE_BAN_LIST)
    {
        $query = TelegramUserList::with(['communities', 'telegramUser', 'listParameters'])->
        whereHas('communities', function ($query) use ($type) {
            $query->where('owner', Auth::user()->id)->where('list_community_telegram_user.type', '=', $type);
        });
        $query->where('type', '=', $type);
        if (!empty($request->input('is_spammer'))) {
            $query->whereHas('listParameters', function ($query) use ($request) {
                $query->where('telegram_user_list_parameters.list_parameter_id', '=', self::SPAMMER);
            });
        }

        if (!empty($request->input('community_id'))) {
            $query->whereHas('communities', function ($query) use ($request) {
                $query->where('communities.id', $request->input('community_id'));
            });
        }

        if (!empty($request->input('telegram_name'))) {
            $query->whereHas('telegramUser', function ($query) use ($request) {
                $query->where('first_name', 'ilike', '%' . $request->input('telegram_name') . '%')
                    ->orWhere('last_name', 'ilike', '%' . $request->input('telegram_name') . '%')
                    ->orWhere('user_name', 'ilike', '%' . $request->input('telegram_name') . '%');
            });
        }
        return $query->orderBy('created_at')->paginate(10);
    }

    public function kick(ApiRequest $request, int $telegram_id)
    {
        foreach ($request->input('community_ids') as $community_id) {
            $community = Community::where('id', $community_id)->first();
            $community_telegram_chat_id = $community->connection->chat_id;
            $this->telegramMainBotService->kickUser(
                config('telegram_bot.bot.botName'),
                $telegram_id,
                $community_telegram_chat_id
            );
            $this->telegramMainBotService->unKickUser(
                config('telegram_bot.bot.botName'),
                $telegram_id,
                $community_telegram_chat_id
            );
            $telegramUserCommunity = TelegramUserCommunity::where('telegram_user_id', $telegram_id)
                ->where('community_id', $community->id)->first();
            $telegramUserCommunity->exit_date = time();
            $telegramUserCommunity->status = 'kicked';
            $telegramUserCommunity->save();
        }

    }

}