<?php

namespace App\Repositories\Community;

use App\Filters\API\CommunitiesFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface CommunityRepositoryContract
{
    public function findCommunityByHash($hash);

    public function create($connection);

    public function getList($request);

    public function update();

    public function getCommunityByChatId($chatId);

    public function getOwnerIdByChatId(int $chatId): ?int;

    public function getCommunityBelongsUserId($userTelegramId);

    public function getAllCommunity();

    public function getCommunityById($id);

    public function isChatBelongsToTeleUserId(int $chatId, int $teleUserId): bool;

    public function getCommunitiesForOwner(int $ownerId, ?CommunitiesFilter $filters = null): Collection;
}
