<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer user_id
 * @property integer subscription_id
 */
class UserSubscription extends Model
{
    use HasFactory;

    protected $table='users_subscriptions';
}
