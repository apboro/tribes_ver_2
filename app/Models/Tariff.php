<?php

namespace App\Models;

use Database\Factories\TariffFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    
    function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    function variants()
    {
        return $this->hasMany(TariffVariant::class, 'tariff_id', 'id');
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
}
