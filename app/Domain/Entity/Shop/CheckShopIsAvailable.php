<?php

namespace App\Domain\Entity\Shop;

use App\Models\Shop;
use Carbon\Carbon;

class CheckShopIsAvailable
{
    public static function isUnavailable(Shop $shop): bool
    {
        $subscription = $shop->user->subscription;

        return Carbon::today() > Carbon::createFromTimestamp($subscription->expiration_date)->addDays(3);
    }
}