<?php

namespace App\Models;

use App\Filters\QueryFilter;
use danog\MadelineProto\stats;
use Database\Factories\TelegramUserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

/**
 * @method TelegramUserFactory factory()
 * @property mixed $accession_date
 * @property mixed $user_name
 * @property mixed $last_name
 * @property mixed $first_name
 * @property mixed $telegram_id
 * @property mixed $user
 * @property mixed $id
 * @property mixed $auth_date
 * @property mixed|null $user_id
 * @property mixed|null $scene
 * @property mixed|null $hash
 * @property mixed|string|null $photo_url
 * @property mixed $userList
 */
class TelegramUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'telegram_users';

    protected $connection = 'main';

    protected $fillable = [
        'telegram_id',
    ];

    protected $hidden = ['id', 'scene', 'scene_for_donate'];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    function communities()
    {
        return $this->belongsToMany(Community::class, 'telegram_users_community', 'telegram_user_id', 'community_id', 'telegram_id', 'id')->withPivot(['accession_date', 'exit_date', 'warnings_count', 'status', 'role']);
    }

    public function publicName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    function tariffVariant()
    {
        return $this->belongsToMany(TariffVariant::class, 'telegram_users_tarif_variants', 'telegram_user_id', 'tarif_variants_id')->withPivot(['days', 'isAutoPay', 'prompt_time', 'created_at', 'recurrent_attempt']);
    }

    function getTariffById($id)
    {
        return $this->tariffVariant()->where('tariff_id', $id)->first();
    }

    function getVariantById($id)
    {
        return $this->tariffVariant()->find($id);
    }

    function getCommunityById($id)
    {
        return $this->communities()->where('community_id', $id)->first();
    }

    function checkStatusTariff($id)
    {
        $tariff = $this->tariffVariant()->where('tariff_id', $id)->first();
        if ($tariff) {
            if ($tariff->price == 0) {
                return 'Пробный период';
            } else return 'Куплен';
        } else return false;
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'telegram_user_id', 'telegram_id');
    }

    public function paymentForCommunity($community_id)
    {
        return $this->payment()->where('community_id', $community_id)->get()->sortBy('created_at');
    }

    function messages()
    {
        return $this->hasMany(TelegramMessage::class, 'telegram_user_id', 'telegram_id');
    }

    function telegramMessageReactions()
    {
        return $this->hasMany(TelegramMessageReaction::class, 'telegram_user_id', 'telegram_id');
    }

    public function hasLeaveCommunity($communityId)
    {
        return $this->communities()->wherePivot('community_id', $communityId)->wherePivotNotNull('exit_date')->exists();
    }

    public function userList()
    {
        return $this->hasMany(
            TelegramUserList::class,
            'telegram_id',
            'telegram_id'
        )->whereHas('communities', function($query){
            $query->where('is_active', 1);
        });
    }

    public static function isCommunityUserOwner($userId): bool
    {
        if($userId === null) {
            log::error('income user Context $ctx is null check this condition');
        }

        return self::where('user_id', '!=', NULL)->where('telegram_id', $userId)->get()->first() !== null;
    }

    public function getTelegramUserName()
    {
        return $this->user_name ?? 'not user name';
    }

    public function connections()
    {
        return $this->hasMany(TelegramConnection::class, 'telegram_user_id', 'telegram_id');
    }
}
