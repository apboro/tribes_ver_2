<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YookassaKey extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'shop_id';
    public $fillable = ['shop_id', 
                        'oauth', 
                        'end_at'];
}