<?php

namespace App\Models;

use App\Traits\Authorable;
use Database\Factories\TariffVariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @method TariffVariantFactory factory() */
class TariffVariant extends Model
{
    use HasFactory, Authorable;

    protected $guarded = [];

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
        return $this->belongsToMany(TelegramUser::class, 'telegram_users_tarif_variants', 'tarif_variants_id', 'telegram_user_id')->withPivot(['days', 'isAutoPay', 'prompt_time', 'created_at', 'end_tarif_date']);
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
}
