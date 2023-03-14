<?php

namespace App\Repositories\Author;

use App\Filters\AudienceFilter;

interface AuthorRepositoryContract
{
    public function assignOutsideAccount($data, $platformIndex);
    public function detachOutsideAccount($telegram_id, $platformIndex);
    public function authorizeTelegram($user, $uuid);
    public function confirmedMobile($request);
    public function numberForCall($request);
    public function resetMobile();
    public function getAudience($filters);
}
