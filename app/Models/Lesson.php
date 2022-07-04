<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('owned', function (Builder $builder) {
//            $builder->whereHas('course', function($q){
//                $q->where('owner', Auth::user()->id);
//            });
        });
    }

    function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    function modules()
    {
        return $this->hasMany(Module::class, 'lesson_id');
    }

    function isOwner($user){
        return $this->course()->first()->isOwner($user);
    }
}
