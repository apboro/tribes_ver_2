<?php

namespace App\Repositories\Community;

use App\Filters\API\CommunitiesFilter;
use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface CommunityRepositoryContract
{
    public function findCommunityByHash($hash);

    public function create($connection);

    public function getList($request);

    public function update();

    public function getCommunityByChatId($chatId): ?Community;

    public function getOwnerIdByChatId(int $chatId): ?int;

    public function getCommunitiesForMemberByTeleUserId($userTelegramId): Collection;

    public function getAllCommunity();

    public function getCommunityById($id): ?Community;

    public function isChatBelongsToTeleUserId(int $chatId, int $teleUserId): bool;

    public function getCommunitiesForOwner(int $ownerId, ?CommunitiesFilter $filters = null): Collection;

    public function getCommunitiesForOwnerByTeleUserId(int $userTelegramId): Collection;
}
