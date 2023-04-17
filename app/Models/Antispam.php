<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antispam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner',
        'del_message_with_link',
        'ban_user_contain_link',
        'del_message_with_forward',
        'ban_user_contain_forward',
        'work_period'
    ];

    public function communities()
    {
        return $this->hasMany(
            Community::class
        );
    }
}
