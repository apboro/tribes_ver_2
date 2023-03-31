<?php

namespace App\Repositories\Community;

use App\Repositories\Telegram\DTO\MessageDTO;


interface CommunityRulesRepositoryContract
{

    public function handleRules(MessageDTO $dtmo);
    public function getCommunityRules(int $community_id);
}
