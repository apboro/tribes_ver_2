<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $room_id
 * @property mixed $user_name
 * @property mixed $user_email
 * @property mixed $user_outer_id
 * @property mixed $ip
 * @property mixed $role
 * @property mixed $attend
 */
class WebinarAnalytics extends Model
{
    use HasFactory;

    protected $table = 'webinar_analytics';

    public static function saveIncomeStatistic(array $collection): void
    {
        $self = new self();
        $self->room_id = $collection['room_id'];
        $self->user_name = $collection['user_name'];
        $self->user_email = $collection['user_email'];
        $self->user_outer_id = $collection['user_outer_id'];
        $self->attend = $collection['attend'];
        $self->ip = $collection['ip'];
        $self->role = $collection['role'];
        $self->save();
    }
}
