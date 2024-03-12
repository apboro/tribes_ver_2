<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnitpayKey extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'user_id';
    public $fillable = ['user_id', 
                        'project_id', 
                        'secretKey'];

}