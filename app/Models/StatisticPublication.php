<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class StatisticPublication extends Model
{

    public $timestamps = false;
    
    protected $guarded = [];
    
    public static function getDayStat(int $publicationId, string $currentDate): self
    {
        $statPost = StatisticPublication::where('publication_id', $publicationId)
            ->where('current_date', $currentDate)
            ->first();
        if ($statPost === null) {
            $statPost = new self();
            $statPost->publication_id = $publicationId;
            $statPost->current_date = $currentDate;
            $statPost->view = 0;
            $statPost->seconds = 0;
        }      

        return $statPost;      
    }
}
