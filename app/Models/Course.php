<?php

namespace App\Models;

use App\Filters\QueryFilter;
use App\Helper\PseudoCrypt;
use App\Traits\Authorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory, Authorable;

    protected $guarded = [];

    protected static function booted()
    {
//        static::addGlobalScope('owned', function (Builder $builder) {

//            if(Auth::user()){
//                dd(21);
//                $builder->where('owner', Auth::user()->id);
//            }
//        });
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }

    function author()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    function course()
    {
        return $this;
    }

    public function payLink($data = null)
    {
        $hash = PseudoCrypt::hash($this->id);
        return route('course.pay', ['hash' => $hash]) ;
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    function attachments()
    {
        return $this->belongsToMany(File::class, 'course_attachments');
    }

    function preview()
    {
        return $this->belongsTo(File::class, 'preview');
    }

    function byers()
    {
        return $this->belongsToMany(User::class, 'course_user')->withPivot(['expired_at', 'byed_at', 'cost']);
    }

    function paymentLink()
    {
        $hash = PseudoCrypt::hash($this->id);
        return route('course.payment', ['hash' => $hash]);
    }

    function getProductWithLesson($lessonId)
    {
        $hash = PseudoCrypt::hash($this->id);
        return route('follower.product', ['hash' => $hash, 'lesson' => $lessonId]);
    }
    function successPageLink()
    {
        $hash = PseudoCrypt::hash($this->id);
        return route('course.pay.success', ['hash' => $hash]);
    }

    function getOrderedLessons()
    {
        return $this->lessons()->orderBy('id', 'ASC')->get();
    }

}
