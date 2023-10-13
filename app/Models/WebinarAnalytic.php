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
class WebinarAnalytic extends Model
{
    use HasFactory;

    /**
     * @inheritdoc
     */
    protected $table = 'webinar_analytics';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'room_id',
        'user_name',
        'user_email',
        'user_outer_id',
        'attend',
        'ip',
        'role',
    ];

    public static function saveIncomeStatistic(array $data): void
    {
        $self = new self();
        $self->fill($data);
        $self->save();
    }
}
