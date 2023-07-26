<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Webinar extends Model
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
