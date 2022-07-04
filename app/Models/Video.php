<?php

namespace App\Models;

use App\Traits\Modulable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory, Modulable;

    protected $table = 'video';

    protected $guarded = [];

    function module()
    {
        return $this->belongsToMany(Module::class, 'module_video', 'video_id', 'module_id');
    }
}
