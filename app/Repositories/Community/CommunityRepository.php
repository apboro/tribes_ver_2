<?php

namespace App\Repositories\Community;

use App\Filters\API\CommunitiesFilter;
use App\Models\Community;
use App\Models\TelegramConnection;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class CommunityRepository implements CommunityRepositoryContract
{

    public function getList($request)
    {
        $user = User::find(Auth::user()->id);
        $user->role_index = User::$role['author'];
        $user->save();
        $userTelegramAccounts = $user->telegramMeta()->pluck('telegram_id')->toArray();


        $list = Community::owned()->active()->with(['tags', 'connection'])->without('donate')->orderBy('created_at', 'DESC');

        $list->whereHas('connection', function ($q) use ($userTelegramAccounts) {
            $q->where('botStatus', 'administrator')
                ->whereIn('telegram_user_id', $userTelegramAccounts);
        });

        if (!empty($request->input('name'))) {
            $list->where('title', 'ilike', '%' . $request->input('name') . '%');
        }

        if ($request->input('tags_names') !== null) {
            $tagsNames = explode(",", $request->input('tags_names')[0]);
            if (!empty(array_filter($request->input('tags_names')))) {
                $list->whereHas('tags', function ($query) use ($tagsNames) {
                    $query->whereIn('name', $tagsNames);
                }, '=', count($tagsNames));
            }
        }

        if ($request->has('rules_uuids')) {
            $uuids_arr = $request->input('rules_uuids');
            $list->where(function ($query) use ($uuids_arr) {
                $query->whereHas('moderationRule', function ($q) use ($uuids_arr) {
                    $q->whereIn('uuid', $uuids_arr);
                })->orWhereHas('onboardingRule', function ($q) use ($uuids_arr) {
                    $q->whereIn('uuid', $uuids_arr);
                })->orWhereHas('communityAntispamRule', function ($q) use ($uuids_arr) {
                    $q->whereIn('uuid', $uuids_arr);
                })->orWhereHas('IfThenRules', function ($q) use ($uuids_arr) {
                    $q->whereIn('uuid', $uuids_arr);
                });
            });

            if (!empty($request->input('date_from'))) {
                $list->whereDate('created_at', '>=', Carbon::createFromTimestamp($request->input('date_from')));
            }

            if (!empty($request->input('date_to'))) {
                $list->whereDate('created_at', '<=', Carbon::createFromTimestamp($request->input('date_to')));
            }

            if (!empty($request->input('telegram_id'))) {
                $list->whereHas('connection', function ($q) use ($request) {
                    $q->where('telegram_user_id', $request->input('telegram_id'));
                });
            }
            $count = $list->count();
            return [
                'list' => $list->skip($request->offset)->take($request->limit)->orderBy('id')->get(),
                'count' => $count,
            ];
        }
    }

        public
        function findCommunityByHash($hash)
        {
            return Community::whereHash($hash)->first();
        }

        public
        function create($connection)
        {
            $community = Community::create([
                'title' => $connection->chat_title,
                'connection_id' => $connection->id
            ]);

            return $community;
        }

        public
        function update()
        {
            dd(1);
//        $this->donateRepo->storeDonateVariant();
            //dd($request);
//        return $community;
        }

        public
        function getCommunityByChatId($chatId): ?Community
        {
            Log::debug('getCommunityByChatId', [$chatId]);
            return Community::whereHas('connection', function ($q) use ($chatId) {
                $q->where('chat_id', $chatId);
            })->first();
        }

        public
        function getCommunitiesForMemberByTeleUserId($userTelegramId): Collection
        {
            return Community::whereHas('followers', function ($query) use ($userTelegramId) {
                $query->where('telegram_id', $userTelegramId);
            })->get();
        }

        public
        function getAllCommunity()
        {
            $community = Community::with('communityOwner', 'connection')->orderBy('created_at', 'desc');
            return $community->paginate(50);
        }

        public
        function getCommunityById($id): ?Community
        {
            return Community::find($id);
        }

        public
        function getCommunitiesForOwner(int $ownerId, ?CommunitiesFilter $filters = null): Collection
        {
            return Community::filter($filters)->where('owner', $ownerId)->get();
        }

        public
        function getUsersCommunities($userId): Collection
        {
            return Community::where('owner', $userId)->get();
        }

        public
        function isChatBelongsToTeleUserId(int $chatId, int $teleUserId): bool
        {
            return TelegramConnection::query()->where([
                'chat_id' => $chatId,
                'telegram_user_id' => $teleUserId,
            ])->exists();
        }

        public
        function getOwnerIdByChatId(int $chatId): ?int
        {
            $tConnect = TelegramConnection::where('chat_id', $chatId)->with('community')->first();
            return $tConnect->community->id ?? null;
        }

        public
        function getCommunitiesForOwnerByTeleUserId(int $userTelegramId): Collection
        {
            return Community::whereHas('connection', function ($query) use ($userTelegramId) {
                $query->where('telegram_user_id', $userTelegramId);
            })->get();
        }
    }
