<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NewDonate extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeOwned($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }

}
