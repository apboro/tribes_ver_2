<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $rules
 * @property mixed $user_id
 * @property mixed $community_id
 */
class UserRule extends Model
{
    use HasFactory;

    protected $table = 'if_then_rules';

    protected $guarded=[];

    protected $hidden = ['created_at'];

    protected $appends = ['type'];
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $casts = [
        'uuid' => 'string'
    ];

    public function getTypeAttribute()
    {
        return 'if_then_rule';
    }

    public function communities()
    {
        return $this->hasMany(Community::class, 'if_then_uuid', 'uuid');
    }
}
