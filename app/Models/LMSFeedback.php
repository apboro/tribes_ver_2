<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LMSFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_id',
        'publication_id',
        'webinar_id',
        'like_material',
        'enough_material',
        'what_to_add',
        'what_to_remove',
    ];

    protected $casts = ['what_to_add'=>'array', 'what_to_remove' => 'array'];

    protected $table = 'lms_feedback';
}
