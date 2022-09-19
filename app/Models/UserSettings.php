<?php

namespace App\Models;

use App\Traits\Modulable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserSettings extends Model
{
    use HasFactory;



    protected $guarded = [];

    protected $table = 'user_settings';

    function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findByUserId(int $id): Collection
    {
        return UserSettings::where('user_id','=',$id)->get()->keyBy('name');
    }
}
