<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer user_id
 * @property integer subscription_id
 * @property boolean isRecurrent
 * @property boolean isActive
 * @property Carbon expiration_date
 */
class UserSubscription extends Model
{
    use HasFactory;

    protected $table='users_subscriptions';
}
