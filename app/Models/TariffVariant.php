<?php

namespace App\Models;

use App\Traits\Authorable;
use Database\Factories\TariffVariantFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use App\Events\TariffPayedEvent;

/** @method TariffVariantFactory factory()
 *  @property int $period;
 *  @property int $id;
 *  @property int $price;
 *  @property string $title;
 *  @property Tariff $tariff;
 *  @property int $views;
 *  @property Community $community;
 */
class TariffVariant extends Model
{
    use HasFactory, Authorable;

    protected $guarded = [];

    protected $fillable = ['recurrent_attempt', 'price', 'tariff_id', 'title','price','period','isActive','inline_link', 'isTest'];

    protected $table = 'tarif_variants';

    function tariff()
    {
        return $this->belongsTo(Tariff::class, 'tariff_id');
    }

    function author()
    {
        return $this->community()->owner()->first();
    }

    public function community()
    {
        return $this->tariff()->first()->community()->first();
    }

    function payFollowers()
    {
        return $this->belongsToMany(TelegramUser::class, 'telegram_users_tarif_variants', 'tarif_variants_id', 'telegram_user_id')->withPivot(['days', 'isAutoPay', 'prompt_time', 'created_at', 'recurrent_attempt']);
    }

    function getFollowersById($id)
    {
        return $this->payFollowers()->where('id', $id)->first();
    }

    function payUsers()
    {
        return $this->belongsToMany(User::class, 'users_tarif_variants', 'tarif_variants_id', 'user_id')->withPivot(['days', 'prompt_time']);
    }

    function getUserById($id)
    {
        return $this->payFollowers()->where('id', $id)->first();
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function getInlineLink($bot = null)
    {
        $bot = $bot ?? env('TELEGRAM_BOT_NAME', '');
        return "@$bot {$this->inline_link}";
    }

    public function isDeactivate()
    {
        return !$this->isActive;
    }

    public static function actionAfterPayment($payment)
    {
        Event::dispatch(new TariffPayedEvent($payment));
    }
}
