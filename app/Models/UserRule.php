<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $rules
 * @property mixed $user_id
 */
class UserRule extends Model
{
    use HasFactory;
    protected $fillable=['rules'];

    protected $casts =['rules'=>'json'];
}
