<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionsDictionary extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];
    protected $table='conditions_types_dictionary';
}
