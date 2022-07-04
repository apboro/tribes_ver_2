<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIp extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function statistic()
    {
        return $this->belongsTo( Statistic::class, 'statistic_id');
    }
}
