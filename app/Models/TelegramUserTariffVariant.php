<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{TariffVariant,
                TelegramUser};
use Doctrine\DBAL\Types\BooleanType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;

class TelegramUserTariffVariant extends Model
{
    protected $table = 'telegram_users_tarif_variants';
    protected $guarded = [];

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

    public function tariffVariant(): BelongsTo
    {
        return $this->belongsTo(TariffVariant::class, 'tarif_variants_id', 'id');
    }

    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id', 'id');
    }

    public function finish(): void
    {
            self::where('telegram_user_id', $this->telegram_user_id)
                        ->where('tarif_variants_id', $this->tarif_variants_id)
                        ->update(['days' => -1]);
    }

    public static function findByDaysAndTime(int $days, bool $isTrial): ?Collection
    {
        return self::where('used_trial', $isTrial)
                            ->with('tariffVariant')
                            ->where('prompt_time', date('H:i'))
                            ->where('days', $days)
                            ->get();
    }

    public static function findEndedTrials(): ?Collection
    {
        return self::findByDaysAndTime(0, true);
    }

    public static function findEndingTrials(): ?Collection
    {
        return self::findByDaysAndTime(1, true);
    }    

    public function isTariffPayed(): bool
    {
        return (bool) self::where('telegram_user_id', $this->telegram_user_id)
            ->where('tarif_variants_id', $this->tariffVariant->tariff->getVariantByPaidType(true)->id)
            ->where('days', '>=', 0)
            ->first();
    }
}
