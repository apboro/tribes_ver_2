<?php

namespace App\Repositories\Community;

use App\Repositories\Telegram\DTO\MessageDTO;


interface CommunityRulesRepositoryContract
{

    public function checkRules(MessageDTO $dtmo);
    public function getCommunityRules(int $community_id);
}
