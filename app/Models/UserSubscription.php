<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $table='users_subscriptions';

    protected $guarded =[];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }
}
