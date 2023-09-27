<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUserTariffVariant extends Model
{
    protected $table = 'telegram_users_tarif_variants';

    public static function findBuyedTariffByTelegramUserId(int $telegramId, int $tariffVariantId)
    {
        $id = (TelegramUser::findByTelegramId($telegramId))->id ?? null;
        if ($id === null) {
            return null;
        }
        
        return self::where('telegram_user_id', $id)
                    ->where('tarif_variants_id', $tariffVariantId)
                    ->first();
    }
}
