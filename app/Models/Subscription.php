<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  @property int $id
 */
class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden=['created_at','updated_at'];

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }


}
