<?php

namespace App\Models;

use App\Repositories\Statistic\StatisticRepository;
use Database\Factories\StatisticFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @method StatisticFactory factory() */
class Statistic extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function userIp()
    {
        return $this->hasMany(UserIp::class, 'statistic_id', 'id');
    }

    public function repository()
    {
        return new StatisticRepository($this);
    }

}
