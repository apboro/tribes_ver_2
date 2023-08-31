<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Webinar extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setAttributeUuid();
        });
    }

    public function setAttributeUuid()
    {
        $this->attributes['uuid'] = Str::uuid();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function prepareType()
    {
       $nowTime = Carbon::now()->format('Y-m-d H:i:s');
       $this->type = '';

        if ($this->start_at <= $nowTime && $this->end_at >= $nowTime) {
            $this->type = 'online';
        } elseif ($this->start_at > $nowTime) {
            $this->type = 'planned';
        } elseif ($this->end_at < $nowTime) {
            $this->type = 'ended';
        }
    }

    public function favourites()
    {
        return $this->hasMany(FavouriteWebinar::class);
    }

    public function prepareIsFavourite($userId)
    {
        $this->is_favourite = $this->hasOne(FavouriteWebinar::class)->where('user_id', $userId)->first() ? true : false;
    } 

}
