<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

/**
 * @property integer user_id
 * @property integer subscription_id
 * @property boolean isRecurrent
 * @property boolean isActive
 * @property Carbon expiration_date
 * @property User user
 */
class UserSubscription extends Model
{
    use HasFactory;

    public const TRIAL_PLAN_ID = 1;
    public const PAY_PLAN_ID = 2;

    protected $table='users_subscriptions';

    protected $guarded =[];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function isExpiredDate(): bool
    {
       return Carbon::createFromTimestamp($this->expiration_date) < Carbon::now();
    }

    public static function getByUser(int $userId): self
    {
        return UserSubscription::where('user_id', $userId)->first();
    }

    public static function setPerioud(int $userId, string $type, $days = 30)
    {
        $expirationDate = Carbon::now()->addDays(env('SUBSCRIPTION_PERIOD', $days))->timestamp;

        $subscription = self::firstOrNew(['user_id' => $userId]);
        $subscription->subscription_id = $type;
        $subscription->expiration_date = $expirationDate;
        $subscription->save();
    }

    public static function checkPeriod(int $userId): bool
    {
        log::info('check period bu user id: ' . $userId);
        return self::getByUser($userId)->isExpiredDate();
    }

    public static function findActiveExpiredSubscriptions()
    {
        return self::where('isActive', true)
            ->where('expiration_date', '<', time())
            ->get();
    }

    public function deactivate()
    {
        $this->isRecurrent = false;
        $this->isActive = false;
        $this->save();
    }

    public function canBeRenew(): bool
    {
        return $this->subscription_id === self::PAY_PLAN_ID && $this->isRecurrent;
    }
}
