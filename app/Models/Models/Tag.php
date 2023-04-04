<?php

namespace App\Models\Models;

use App\Models\Community;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];
    protected $hidden=['created_at', 'updated_at'];

    public function communities(){
        return $this->belongsToMany(Community::class,'community_tag','tag_id','community_id');
    }

    public function scopeOwned($query)
    {
        return $query->where('user_id', '=', Auth::user()->id);
    }
}
