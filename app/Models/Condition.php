<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Condition extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded=[];
    public $timestamps=false;

    public function action(): HasOne
    {
        return $this->hasOne(Action::class,'group_uuid','group_uuid');
    }

}
