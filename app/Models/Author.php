<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Publication;
use App\Models\Webinar;

class Author extends Model
{
    use HasFactory;

    public $guarded = [];

    public function publications()
    {
     return $this->hasMany(Publication::class);   
    }

    public function webinars()
    {
     return $this->hasMany(Webinar::class);   
    }
}
