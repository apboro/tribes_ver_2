<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitedWebinar extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function countByUser(int $userId)
    {
        return self::where('user_id', $userId)->count();
    }

    public static function getIdsByUser(int $userId, $offset = 0, $limit = 3)
    {
        return self::where('user_id', $userId)
                    ->orderByDesc('last_visited')
                    ->offset($offset)
                    ->limit($limit)
                    ->pluck('webinar_id')->toArray();
    } 
}
