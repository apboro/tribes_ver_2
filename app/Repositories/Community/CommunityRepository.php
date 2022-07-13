<?php

namespace App\Repositories\Community;

use App\Filters\API\CommunitiesFilter;
use App\Models\Community;
use App\Models\TelegramConnection;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CommunityRepository implements CommunityRepositoryContract
{

    public function getList($request)
    {
        $user = User::find(Auth::user()->id);
        $user->role_index = User::$role['author'];
        $user->save();
        $ty = Auth::user()->telegramMeta()->first();

        $list = Community::owned()->whereHas('connection', function ($q) use ($ty) {
            $q->where('telegram_user_id', $ty ? $ty->telegram_id : 1);
        })->paginate(20);

        return $list;
    }

    public function findCommunityByHash($hash)
    {
        return Community::whereHash($hash)->first();
    }

    public function create($connection)
    {
        $community = Community::create([
            'title' => $connection->chat_title,
            'connection_id' => $connection->id
        ]);

        return $community;
    }

    public function update()
    {
        dd(1);
//        $this->donateRepo->storeDonateVariant();
        //dd($request);
//        return $community;
    }

    public function getCommunityByChatId($chatId): ?Community
    {
        return Community::whereHas('connection', function ($q) use ($chatId) {
            $q->where('chat_id', $chatId);
        })->first();
    }

    public function getCommunityBelongsUserId($userTelegramId)
    {
        return Community::whereHas('followers', function ($query) use ($userTelegramId) {
            $query->where('telegram_id', $userTelegramId);
        })->get();
    }

    public function getAllCommunity()
    {
        return Community::all();
    }

    public function getCommunityById($id)
    {
        return Community::find($id);
    }

    public function getCommunitiesForOwner(int $ownerId, ?CommunitiesFilter $filters = null): Collection
    {
        return Community::filter($filters)->where('owner', $ownerId)->get();
    }

    public function isChatBelongsToTeleUserId(int $chatId, int $teleUserId): bool
    {
        return TelegramConnection::query()->where([
            'chat_id' => $chatId,
            'telegram_user_id' => $teleUserId,
        ])->exists();
    }

    public function getOwnerIdByChatId(int $chatId): ?int
    {
        $tConnect = TelegramConnection::where('chat_id', $chatId)->with('community')->first();
        return $tConnect->community->id ?? null;
    }
}
