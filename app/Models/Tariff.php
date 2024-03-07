<?php

namespace App\Models;

use Database\Factories\TariffFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use App\Helper\PseudoCrypt;
use Illuminate\Support\Facades\Log;

/** @method TariffFactory factory()
 * @property mixed $main_image
 * @property mixed $thanks_image
 * @property mixed $thanks_message_is_active
 * @property mixed $tariff_is_payable
 * @property mixed $thanks_message
 * @property mixed $test_period_is_active
 * @property mixed $user_id
 */
class Tariff extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['variants'];

    const FRONTEND_TARIFF_PAGE = '/app/public/tariff/';
    
    function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    function variants()
    {
        return $this->hasMany(TariffVariant::class, 'tariff_id', 'id')->orderBy('id');
    }

    private function activeVariants(): HasMany
    {
        return $this->variants()->where('isActive', true)->where('isPersonal', false);
    }

    public function getActiveVariants(): Collection
    {
        return $this->activeVariants()->get();
    }

    /** @see spodial_backend/app/Services/Telegram/MainComponents/MainBotCommands.php:1311  */
    public function findVariantsByTgUserId(int $tgUserId): Collection
    {
        $variantIdList = $this->variants()->pluck('id')->toArray();
        log::info('find tariffvariant By tg user id '. json_encode($variantIdList, JSON_UNESCAPED_UNICODE));

        return TelegramUserTariffVariant::findTariffVariantsByUser($variantIdList, $tgUserId);
    }

    public function variantTest()
    {
        return $this->variants()->where('isTest', true);
    }

    public function variantPaid()
    {
        return $this->variants()->where('isTest', false);
    }

    public function getVariantByPaidType(bool $isPaid)
    {
        return $this->variants()->where('isTest', !$isPaid)->first();
    }

    public function tariffCommunityUsers(): HasManyThrough
    {
        return $this->hasManyThrough(TelegramUserCommunity::class, Community::class, 'id', 'community_id','community_id')
            ->whereNull('exit_date')->whereRole('member');
    }

    public function scopeGetTrialVariant($query)
    {
        return $this->variants()->where('title', 'Пробный период')->first();
    }

    public function scopeOwned($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }

    public function getTariffVariants($inlineLink = null)
    {
        $builder = TariffVariant::where('tariff_id', $this->id)
            ->where('isActive', true)
            ->orderBy('price', 'ASC');
        if($inlineLink) {
            $builder->where('inline_link', '=', $inlineLink);
        } else {
            $builder->where('isPersonal', false);
        }
        $tariffVariants = $builder->get();

        return $tariffVariants;
    }

    public function getMainImage()
    {
        return $this->belongsTo(File::class,'main_image_id')->first();
    }
    public function getWelcomeImage()
    {
        return $this->belongsTo(File::class,'welcome_image_id')->first();
    }
    public function getReminderImage()
    {
        return $this->belongsTo(File::class,'reminder_image_id')->first();
    }
    public function getThanksImage()
    {
        return $this->belongsTo(File::class,'thanks_image_id')->first();
    }

    public function getPublicationImage()
    {
        return $this->belongsTo(File::class,'publication_image_id')->first();
    }

    public static function baseData()
    {
        return [
            'test_period' => 0,
            'welcome_description' => null, //__('tariff.welcome_default_description'),
            'reminder_description' =>  __('tariff.reminder_default_description'),
            'thanks_description' =>  __('tariff.success_default_description'),
            'publication_description' => __('tariff.available_rates')
        ];
    }

    public function getInlineLink($bot = null)
    {
        $bot = $bot ?? env('TELEGRAM_BOT_NAME', '');
        return "@$bot {$this->inline_link}";
    }

    public static function preparePaymentLink(string $inlineLink, bool $TryTrial, int $telegramUserId): string
    {
        $params['telegram_user_id'] = $telegramUserId;
        if ($TryTrial) {
            $params['try_trial'] = $TryTrial;
        }
        
        return config('app.frontend_url') . Tariff::FRONTEND_TARIFF_PAGE . $inlineLink . '/pay/' . '?' . http_build_query($params);
    }
}
