<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property mixed $rules
 * @property mixed $user_id
 * @property bool|mixed $title
 * @property mixed $id
 * @property mixed $greeting_message_id
 * @property false|mixed|string|null $question_image
 * @property false|mixed|string|null $greeting_image
 */
class Onboarding extends Model
{
//    protected $casts = ['rules'=>'json'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['type'];
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';
    protected $casts = [
        'uuid' => 'string'
    ];


    use HasFactory;

    public function getTypeAttribute()
    {
        return 'onboarding_rule';
    }

    public function communities()
    {
        return $this->hasMany(Community::class, 'onboarding_uuid', 'uuid');
    }

}
