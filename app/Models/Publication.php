<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Publication extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    public function parts(): HasMany
    {
        return $this->hasMany(PublicationPart::class);
    }

    public function favourites()
    {
        return $this->hasMany(FavouritePublication::class);
    }

    public function visited()
    {
        return $this->hasMany(VisitedPublication::class);
    }
}
