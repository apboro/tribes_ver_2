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

    protected $table = 'user_community_rules';

    protected $guarded=[];

    protected $casts =['rules'=>'json'];

    protected $hidden = ['created_at', 'updated_at'];
}
