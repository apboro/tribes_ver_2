<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publication extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parts(): HasMany
    {
        return $this->hasMany(PublicationPart::class);
    }
}
