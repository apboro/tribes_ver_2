<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavouriteWebinar extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function webinars(): BelongsTo
    {
        return $this->belongsTo(Webinar::class);
    }
}
